<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Book;

class FeaturedBooksCarousel extends Component
{
    public function render()
    {
        // Buscar os 5 livros mais recentes com seus autores e editoras
        $books = Book::with(['authors', 'publisher'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $booksArray = $books->map(function ($book) {
            $authors = $book->authors->pluck('name')->join(', ');

            return [
                'name' => $book->name,
                'cover_image' => $book->cover_image ?: '/images/book-placeholder.jpg',
                'bibliography' => $book->bibliography,
                'isbn' => $book->isbn,
                'price' => $book->price,
                'authors' => $authors,
                'publisher' => $book->publisher ? $book->publisher->name : 'Desconhecido',
                'id' => $book->id
            ];
        })->values()->toArray();

        return view('livewire.featured-books-carousel', [
            'books' => $booksArray
        ]);
    }
}
