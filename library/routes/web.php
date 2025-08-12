<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PublisherController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    // Admin routes
    Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
    Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
    Route::get('/admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');
    Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
    Route::get('/admins/{admin}', [AdminController::class, 'show'])->name('admins.show');
    Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');
});

Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
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
