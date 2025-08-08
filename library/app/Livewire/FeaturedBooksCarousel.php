<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Book;

class FeaturedBooksCarousel extends Component
{
    public function render()
    {
        // Buscar os 5 livros mais recentes
        $books = Book::orderBy('created_at', 'desc')->take(5)->get();
        $booksArray = $books->map(function ($book) {
            return [
                'name' => $book->name,
                'cover_image' => $book->cover_image,
                'bibliography' => $book->bibliography, // Não limitamos aqui para manter os dados originais
                'isbn' => $book->isbn,
            ];
        })->values()->toArray();
        return view('livewire.featured-books-carousel', [
            'books' => $booksArray
        ]);
    }
}
