<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Http\Request;
use App\Services\GoogleApiService;
use Illuminate\Pagination\LengthAwarePaginator;

class GoogleApiController extends Controller
{
    public function search(GoogleApiService $googleBooks, Request $request)
    {
        $query = $request->input('q');
        $books = [];

        if ($query) {
            $books = $googleBooks->searchBooks($query, 40);
        }

        $filtered = array_filter($books, function ($book) {
            $volumeInfo = $book['volumeInfo'] ?? [];
            $saleInfo = $book['saleInfo'] ?? [];
            $title = $volumeInfo['title'] ?? null;
            $authors = $volumeInfo['authors'] ?? null;
            $publisher = $volumeInfo['publisher'] ?? null;
            $thumbnail = $volumeInfo['imageLinks']['thumbnail'] ?? null;
            $price = $saleInfo['listPrice']['amount'] ?? null;

            // Extract ISBN (prefer ISBN_13, fallback to ISBN_10)
            $isbn = null;
            if (!empty($volumeInfo['industryIdentifiers'])) {
                foreach ($volumeInfo['industryIdentifiers'] as $identifier) {
                    if ($identifier['type'] === 'ISBN_13') {
                        $isbn = $identifier['identifier'];
                        break;
                    }
                }
                if (!$isbn) {
                    foreach ($volumeInfo['industryIdentifiers'] as $identifier) {
                        if ($identifier['type'] === 'ISBN_10') {
                            $isbn = $identifier['identifier'];
                            break;
                        }
                    }
                }
            }

            return !empty($isbn)
                && !empty($title)
                && !empty($authors)
                && !empty($publisher)
                && !empty($price)
                && !empty($thumbnail);
        });

        $perPage = 10;
        $page = request()->input('page', 1); // current page, default = 1
        $filteredItems = collect($filtered)
            ->sortBy('volumeInfo.title')
            ->values();

        $paginated = new LengthAwarePaginator(
            $filteredItems->forPage($page, $perPage),
            $filteredItems->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('googlebooks.search', [
            'books' => $paginated,
            'query' => $query
        ]);
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
