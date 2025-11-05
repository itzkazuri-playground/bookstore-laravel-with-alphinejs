<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorStatistics extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'total_voters',
        'voters_above_5',
        'average_rating',
        'last_30_days_avg',
        'previous_30_days_avg',
        'trending_score',
        'best_rated_book_id',
        'worst_rated_book_id',
        'last_calculated_at',
    ];

    protected $casts = [
        'average_rating' => 'decimal:2',
        'last_30_days_avg' => 'decimal:2',
        'previous_30_days_avg' => 'decimal:2',
        'trending_score' => 'decimal:2',
        'last_calculated_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function bestRatedBook()
    {
        return $this->belongsTo(Book::class, 'best_rated_book_id');
    }

    public function worstRatedBook()
    {
        return $this->belongsTo(Book::class, 'worst_rated_book_id');
    }
}