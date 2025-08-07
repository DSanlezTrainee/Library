<?php

namespace App\Models;

use App\Casts\Encrypted;
use App\Traits\EncryptedSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory , EncryptedSearch;

    protected $fillable = [
        'name',
        'photo'
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'name' => Encrypted::class,  // Cifrar nome do autor
            'photo' => Encrypted::class, // Cifrar caminho da foto
        ];
    }

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }
}
