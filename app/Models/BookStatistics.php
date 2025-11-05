<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookStatistics extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'average_rating',
        'total_voters',
        'last_7_days_avg',
        'last_30_days_avg',
        'previous_30_days_avg',
        'last_calculated_at',
    ];

    protected $casts = [
        'average_rating' => 'decimal:2',
        'last_7_days_avg' => 'decimal:2',
        'last_30_days_avg' => 'decimal:2',
        'previous_30_days_avg' => 'decimal:2',
        'last_calculated_at' => 'datetime',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}