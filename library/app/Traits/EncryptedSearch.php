<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait EncryptedSearch
{
    /**
     * Busca exata por um campo cifrado
     */
    public function scopeWhereEncrypted($query, $field, $value)
    {
        try {
            $encryptedValue = Crypt::encryptString($value);
            return $query->where($field, $encryptedValue);
        } catch (\Exception $e) {
            return $query->whereRaw('1 = 0'); // Retorna query vazia em caso de erro
        }
    }

    /**
     * Busca "like" em campo cifrado
     * ATENÇÃO: Muito ineficiente - decifra todos os registros
     * Use apenas em datasets pequenos ou implemente índices especiais
     */
    public function scopeWhereLikeEncrypted($query, $field, $value)
    {
        $results = $query->whereNotNull($field)->get()->filter(function ($model) use ($field, $value) {
            try {
                $decryptedValue = $model->getAttribute($field);
                return stripos($decryptedValue, $value) !== false;
            } catch (\Exception $e) {
                return false;
            }
        });

        // Retorna uma Collection, não um Query Builder
        return $results;
    }

    /**
     * Busca "like" em campo cifrado com suporte a paginação manual
     * Retorna um array com dados paginados
     */
    public static function searchEncryptedPaginated($field, $value, $perPage = 10, $page = 1)
    {
        $query = static::whereNotNull($field);
        $results = $query->get()->filter(function ($model) use ($field, $value) {
            try {
                $decryptedValue = $model->getAttribute($field);
                return stripos($decryptedValue, $value) !== false;
            } catch (\Exception $e) {
                return false;
            }
        });

        // Ordenar e paginar manualmente
        $sortedResults = $results->sortBy($field);
        $total = $sortedResults->count();
        $offset = ($page - 1) * $perPage;
        $paginatedResults = $sortedResults->slice($offset, $perPage)->values();

        return [
            'data' => $paginatedResults,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
        ];
    }

    /**
     * Busca por múltiplos valores em um campo cifrado
     */
    public function scopeWhereInEncrypted($query, $field, array $values)
    {
        $encryptedValues = [];
        foreach ($values as $value) {
            try {
                $encryptedValues[] = Crypt::encryptString($value);
            } catch (\Exception $e) {
                // Ignora valores que não podem ser cifrados
            }
        }

        return $query->whereIn($field, $encryptedValues);
    }

    /**
     * Helper para converter Collection em LengthAwarePaginator
     * Útil para manter compatibilidade com views que usam links()
     */
    public static function paginateCollection($collection, $perPage = 10, $pageName = 'page')
    {
        $currentPage = request()->get($pageName, 1);
        $offset = ($currentPage - 1) * $perPage;
        $currentPageItems = $collection->slice($offset, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => $pageName,
            ]
        );
    }

    /**
     * Pesquisa avançada para livros em múltiplos campos cifrados
     * Pesquisa em: nome do livro, ISBN, nome do autor, nome da editora
     * Este método deve ser usado apenas no modelo Book
     */
    public static function searchBooksAdvanced($searchTerm)
    {
        if (empty($searchTerm)) {
            return collect();
        }

        $results = collect();

        // 1. Pesquisar por nome do livro (cifrado)
        $booksByName = static::whereLikeEncrypted('name', $searchTerm);
        $results = $results->merge($booksByName);

        // 2. Pesquisar por ISBN (cifrado)
        $booksByIsbn = static::whereLikeEncrypted('isbn', $searchTerm);
        $results = $results->merge($booksByIsbn);

        // 3. Pesquisar por nome do autor (cifrado)
        $authorsWithSearchTerm = \App\Models\Author::whereLikeEncrypted('name', $searchTerm);
        if ($authorsWithSearchTerm->isNotEmpty()) {
            $authorIds = $authorsWithSearchTerm->pluck('id')->toArray();
            $booksByAuthor = static::whereHas('authors', function ($query) use ($authorIds) {
                $query->whereIn('author_book.author_id', $authorIds);
            })->get();
            $results = $results->merge($booksByAuthor);
        }

        // 4. Pesquisar por nome da editora (cifrado)
        $publishersWithSearchTerm = \App\Models\Publisher::whereLikeEncrypted('name', $searchTerm);
        if ($publishersWithSearchTerm->isNotEmpty()) {
            $publisherIds = $publishersWithSearchTerm->pluck('id')->toArray();
            $booksByPublisher = static::whereIn('publisher_id', $publisherIds)->get();
            $results = $results->merge($booksByPublisher);
        }

        // Remover duplicados e carregar relacionamentos para exibição eficiente
        $uniqueBooks = $results->unique('id');
        $bookIds = $uniqueBooks->pluck('id')->toArray();

        if (empty($bookIds)) {
            return collect();
        }

        return static::with(['authors', 'publisher'])
            ->whereIn('id', $bookIds)
            ->get()
            ->sortBy('name');
    }
}
