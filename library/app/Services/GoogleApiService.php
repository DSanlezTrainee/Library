<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleApiService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function searchBooks(string $query, int $maxResults = 10)
    {
        $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
            'q' => $query,
            'maxResults' => $maxResults,
        ]);

        if ($response->failed()) {
            return 'No books found';
        }
        
        return $response->json()['items'] ?? [];
    }
}
