<?php

namespace App\Models;

use App\Casts\Encrypted;
use App\Traits\EncryptedSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory, EncryptedSearch;

    protected $fillable = [
        'isbn',
        'name',
        'publisher_id',
        'bibliography',
        'cover_image',
        'price'
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'isbn' => Encrypted::class,
            'name' => Encrypted::class,
            'bibliography' => Encrypted::class,
            'cover_image' => Encrypted::class,
            'price' => Encrypted::class,
        ];
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    /**
     * Get the formatted price attribute.
     */
    public function getFormattedPriceAttribute()
    {
        return $this->price ? (float) $this->price : 0;
    }

    public function requisitions()
    {
        return $this->hasMany(Requisition::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
