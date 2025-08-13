<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Requisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'sequential_number',
        'start_date',
        'end_date',
        'actual_return_date',
        'status',
        'admin_confirmed',
        'citizen_photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    protected static function booted()
    {
        static::creating(function ($requisition) {
            $lastNumber = self::max('sequential_number') ?? 0;
            $requisition->sequential_number = $lastNumber + 1;
        });
    }
}
