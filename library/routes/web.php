<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PublisherController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'is_admin'])->group(function () {});

Route::post('/books', [BookController::class, 'store'])->name('books.store');
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
Route::get('/books', [BookController::class, 'index'])->name('books.index');

Route::get('/authors', [AuthorController::class, 'index']);
Route::get('/authors/{author}', [AuthorController::class, 'show']);

Route::get('/publishers', [PublisherController::class, 'index']);
Route::get('publishers/{publisher}', [PublisherController::class, 'show']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
