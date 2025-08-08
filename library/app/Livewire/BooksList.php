<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Author;
use Livewire\Component;
use App\Exports\BooksExport;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;


class BooksList extends Component
{
    use WithPagination;

    public $search = '';
    public $searchField = 'all';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function export()
    {
        $fileName = 'books_export_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new BooksExport($this->search), $fileName);
    }

    public function getSearchFieldLabelProperty()
    {
        switch ($this->searchField) {
            case 'name':
                return 'Book Name';
            case 'author':
                return 'Author';
            case 'publisher':
                return 'Publisher';
            case 'isbn':
                return 'ISBN';
            default:
                return 'All';
        }
    }

    public function render()
    {
        // Buscar todos os livros com relacionamentos necessários
        if (!empty($this->search)) {
            $booksCollection = $this->searchBooksByField($this->searchField, $this->search);
        } else {
            $booksCollection = Book::with(['authors', 'publisher'])->get();
        }

        // Ordenar a coleção em PHP para todos os campos cifrados
        $booksCollection = $this->applySortingToCollection($booksCollection);

        // Paginar manualmente
        $books = Book::paginateCollection($booksCollection, 10);

        return view('livewire.books-list', [
            'books' => $books,
            'searchFieldLabel' => $this->searchFieldLabel,
        ]);
    }

    private function searchBooksByField($field, $search)
    {
        // Use the encrypted search helpers for each field
        $results = collect();
        if ($field === 'all') {
            return Book::searchBooksAdvanced($search);
        }
        if ($field === 'name') {
            return Book::whereLikeEncrypted('name', $search);
        }
        if ($field === 'isbn') {
            return Book::whereLikeEncrypted('isbn', $search);
        }
        if ($field === 'author') {
            $authors = Author::whereLikeEncrypted('name', $search);
            if ($authors->isNotEmpty()) {
                $authorIds = $authors->pluck('id')->toArray();
                $books = Book::whereHas('authors', function ($query) use ($authorIds) {
                    $query->whereIn('author_book.author_id', $authorIds);
                })->get();
                return $books;
            }
            return collect();
        }
        if ($field === 'publisher') {
            $publishers = \App\Models\Publisher::whereLikeEncrypted('name', $search);
            if ($publishers->isNotEmpty()) {
                $publisherIds = $publishers->pluck('id')->toArray();
                $books = Book::whereIn('publisher_id', $publisherIds)->get();
                return $books;
            }
            return collect();
        }
        return collect();
    }

    private function applySortingToCollection($collection)
    {
        switch ($this->sortField) {
            case 'author':
                $sorted = $collection->sortBy(function ($book) {
                    return optional($book->authors->first())->name ?? '';
                }, SORT_NATURAL | SORT_FLAG_CASE, $this->sortDirection === 'desc');
                break;
            case 'publisher':
                $sorted = $collection->sortBy(function ($book) {
                    return optional($book->publisher)->name ?? '';
                }, SORT_NATURAL | SORT_FLAG_CASE, $this->sortDirection === 'desc');
                break;
            case 'price':
                $sorted = $collection->sortBy(function ($book) {
                    return $book->formatted_price;
                }, SORT_NUMERIC, $this->sortDirection === 'desc');
                break;
            default:
                $sorted = $collection->sortBy(function ($book) {
                    // Para campos cifrados, usar accessor
                    return $book->{$this->sortField} ?? '';
                }, SORT_NATURAL | SORT_FLAG_CASE, $this->sortDirection === 'desc');
                break;
        }
        return $sorted->values();
    }
}
