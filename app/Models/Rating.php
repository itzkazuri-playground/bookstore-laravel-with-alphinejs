<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'rating',
        'voter_identifier',
        'rated_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'rated_at' => 'datetime',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}