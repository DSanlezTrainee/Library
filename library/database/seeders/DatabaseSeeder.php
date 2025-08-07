<?php

namespace Database\Seeders;

use App\Models\Book;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Cria 5 editoras
        Publisher::factory(5)->create();

        // Cria 10 autores
        Author::factory(10)->create();

        // Cria 20 livros, associando editoras e autores
        Book::factory(20)->create()->each(function ($book) {
            // Associa de 1 a 3 autores aleatórios a cada livro
            $authors = Author::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $book->authors()->attach($authors);
        });
    }
}
