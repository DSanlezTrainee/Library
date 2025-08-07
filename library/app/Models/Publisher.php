<?php

namespace App\Models;

use App\Casts\Encrypted;
use App\Traits\EncryptedSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publisher extends Model
{
    /** @use HasFactory<\Database\Factories\PublisherFactory> */
    use HasFactory, EncryptedSearch;

     protected $fillable = [
        'name',
        'logo'
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'name' => Encrypted::class, // Cifrar nome da editora
            'logo' => Encrypted::class, // Cifrar caminho do logo
        ];
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
