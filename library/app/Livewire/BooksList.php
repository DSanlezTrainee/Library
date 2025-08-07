<?php

namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;
use App\Exports\BooksExport;
use Maatwebsite\Excel\Excel;
use Livewire\WithPagination;

class BooksList extends Component
{
    use WithPagination;

    public $search = '';

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

            // Converter para paginação compatível com links()
            $books = Book::paginateCollection($booksCollection, 10);
        } else {
            $books = Book::with(['authors', 'publisher'])->orderBy('name')->paginate(10);
        }

        return view('livewire.books-list', [
            'books' => $books,
        ]);
    }
}
