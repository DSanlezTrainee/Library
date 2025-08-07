<?php

namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;
use App\Exports\BooksExport;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithPagination;

class BooksList extends Component
{
    use WithPagination;

    public $search = '';
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

    public function render()
    {
        // Fetch books filtered by search, paginate 10 per page (optional)
        if (!empty($this->search)) {
            // Pesquisa avançada em múltiplos campos cifrados
            // Pesquisa em: nome do livro, ISBN, nome do autor, nome da editora
            $booksCollection = Book::searchBooksAdvanced($this->search);

            // Aplicar ordenação na coleção
            $booksCollection = $this->applySortingToCollection($booksCollection);

            // Converter para paginação compatível com links()
            $books = Book::paginateCollection($booksCollection, 10);
        } else {
            $query = Book::with(['authors', 'publisher']);

            // Aplicar ordenação baseada no campo selecionado
            if ($this->sortField === 'author') {
                $query->join('author_book', 'books.id', '=', 'author_book.book_id')
                    ->join('authors', 'author_book.author_id', '=', 'authors.id')
                    ->orderBy('authors.name', $this->sortDirection)
                    ->select('books.*')
                    ->distinct();
            } elseif ($this->sortField === 'publisher') {
                $query->join('publishers', 'books.publisher_id', '=', 'publishers.id')
                    ->orderBy('publishers.name', $this->sortDirection)
                    ->select('books.*');
            } else {
                $query->orderBy($this->sortField, $this->sortDirection);
            }

            $books = $query->paginate(10);
        }

        return view('livewire.books-list', [
            'books' => $books,
        ]);
    }

    private function applySortingToCollection($collection)
    {
        if ($this->sortField === 'author') {
            return $this->sortDirection === 'asc'
                ? $collection->sortBy(function ($book) {
                    return $book->authors->first()->name ?? '';
                })
                : $collection->sortByDesc(function ($book) {
                    return $book->authors->first()->name ?? '';
                });
        } elseif ($this->sortField === 'publisher') {
            return $this->sortDirection === 'asc'
                ? $collection->sortBy('publisher.name')
                : $collection->sortByDesc('publisher.name');
        } else {
            return $this->sortDirection === 'asc'
                ? $collection->sortBy($this->sortField)
                : $collection->sortByDesc($this->sortField);
        }
    }
}
