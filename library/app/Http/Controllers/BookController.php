<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with(['authors', 'publisher'])->orderBy('name', 'asc')->simplePaginate(10);
        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authors = Author::orderBy('name', 'asc')->get();
        $publishers = Publisher::orderBy('name', 'asc')->get();

        return view('books.create', [
            'authors' => $authors,
            'publishers' => $publishers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'isbn' => 'required|digits:13',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,png|max:2048',
            'author_ids' => 'required|array',
            'author_ids.*' => 'exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'bibliography' => 'nullable|string',
        ], [
            'isbn.digits' => 'The ISBN must be exactly 13 digits (numbers only).',
            'author_ids.required' => 'Please select at least one author.',
            'publisher_id.required' => 'Please select a publisher.',
            'price.required' => 'Please enter a price.',
            'price.numeric' => 'Please enter a valid number.',
        ]);

        // Check for duplicate ISBN manually since we're using encrypted fields
        $isbnExists = Book::all()->filter(function ($book) use ($request) {
            return $book->isbn == $request->isbn;
        })->count() > 0;

        if ($isbnExists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['isbn' => 'A book with this ISBN already exists.']);
        }

        $authorIds = $attributes['author_ids'];
        unset($attributes['author_ids']);


        $book = Book::create($attributes);

        $book->authors()->attach($authorIds);
        return redirect()->route('books.index')->with('success', 'Book registered successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('books.show', ['book' => $book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $authors = Author::orderBy('name', 'asc')->get();
        $publishers = Publisher::orderBy('name', 'asc')->get();
        $book->load('authors'); // Make sure the authors relationship is loaded

        return view('books.edit', [
            'book' => $book,
            'authors' => $authors,
            'publishers' => $publishers
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'isbn' => 'required|digits:13',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,png|max:2048',
            'author_ids' => 'required|array',
            'author_ids.*' => 'exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'bibliography' => 'nullable|string',
        ], [
            'isbn.digits' => 'The ISBN must be exactly 13 digits (numbers only).',
            'author_ids.required' => 'Please select at least one author.',
            'publisher_id.required' => 'Please select a publisher.',
            'price.required' => 'Please enter a price.',
            'price.numeric' => 'Please enter a valid number.',
        ]);

        // Check for duplicate ISBN manually since we're using encrypted fields
        // Only check other books (not the current one)
        $isbnExists = Book::all()->filter(function ($existingBook) use ($request, $book) {
            return $existingBook->isbn == $request->isbn && $existingBook->id != $book->id;
        })->count() > 0;

        if ($isbnExists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['isbn' => 'A book with this ISBN already exists.']);
        }

        $authorIds = $attributes['author_ids'];
        unset($attributes['author_ids']);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // TODO: Add image processing logic if needed
        }

        $book->update($attributes);

        // Sync authors (remove existing relations and add new ones)
        $book->authors()->sync($authorIds);

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // First detach all authors from the book
        $book->authors()->detach();

        // Then delete the book
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }
}
