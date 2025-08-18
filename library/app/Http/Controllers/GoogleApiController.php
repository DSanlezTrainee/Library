<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Http\Request;
use App\Services\GoogleApiService;

class GoogleApiController extends Controller
{
    public function search(GoogleApiService $googleBooks, Request $request)
    {
        $query = $request->input('q');
        $books = [];

        if ($query) {
            $books = $googleBooks->searchBooks($query);
        }

        return view('googlebooks.search', compact('books', 'query'));
    }

    public function import(Request $request)
    {
        $bookData = $request->input('book');

        $publisher = null;
        if (!empty($bookData['publisher'])) {
            $publisher = Publisher::firstOrCreate(['name' => $bookData['publisher']]);
        }

        $book = Book::create([
            'isbn' => $bookData['isbn'] ?? null,
            'name' => $bookData['title'] ?? null,
            'author' => isset($bookData['authors']) ? implode(', ', $bookData['authors']) : null,
            'publisher_id' => $publisher ? $publisher->id : null,
            'bibliography' => $bookData['description'] ?? null,
            'price' => $bookData['price'] ?? null,
            'cover_image' => $bookData['thumbnail'] ?? null,
        ]);

        if (!empty($bookData['authors'])) {
            foreach ($bookData['authors'] as $authorName) {
                $author = Author::firstOrCreate(['name' => $authorName]);
                $book->authors()->syncWithoutDetaching($author->id);
            }
        }

        return redirect()->route('books.index')
            ->with('success', 'Book imported successfully!');
    }
}
