<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\GoogleApiController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\RequisitionController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'is_admin'])->group(function () {
    //users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    //books 
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

// Public routes

Route::middleware(['auth'])->group(function () {

    // Cidadãos e Admin podem acessar o menu requisições
    Route::get('/requisitions', [RequisitionController::class, 'index'])->name('requisitions.index');

    // Mostrar formulário para criar nova requisição
    Route::get('/requisitions/create', [RequisitionController::class, 'create'])->name('requisitions.create');

    // Armazenar nova requisição
    Route::post('/requisitions', [RequisitionController::class, 'store'])->name('requisitions.store');

    // Detalhe da requisição
    Route::get('/requisitions/{requisition}', [RequisitionController::class, 'show'])->name('requisitions.show');

    Route::middleware(['is_admin'])->group(function () {
        Route::get('/requisitions/{requisition}/edit', [RequisitionController::class, 'edit'])->name('requisitions.edit');
        Route::put('/requisitions/{requisition}', [RequisitionController::class, 'update'])->name('requisitions.update');
    });
});

Route::get('/googlebooks/search', [GoogleApiController::class, 'search'])->name('googlebooks.search');
Route::post('/googlebooks/import', [GoogleApiController::class, 'import'])->name('googlebooks.import');

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
