<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bio',
        'country',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function authorStatistics()
    {
        return $this->hasOne(AuthorStatistics::class);
    }

    public function bestRatedBook()
    {
        return $this->hasOneThrough(
            Book::class,
            AuthorStatistics::class,
            'author_id', // Foreign key on author_statistics table
            'id',        // Foreign key on books table
            'id',        // Local key on authors table
            'best_rated_book_id' // Local key on author_statistics table
        );
    }

    public function worstRatedBook()
    {
        return $this->hasOneThrough(
            Book::class,
            AuthorStatistics::class,
            'author_id', // Foreign key on author_statistics table
            'id',        // Foreign key on books table
            'id',        // Local key on authors table
            'worst_rated_book_id' // Local key on author_statistics table
        );
    }
}