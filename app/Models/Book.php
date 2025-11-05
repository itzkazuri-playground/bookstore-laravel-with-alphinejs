<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author_id',
        'isbn',
        'publisher',
        'publication_year',
        'description',
        'availability_status',
        'store_location',
    ];

    protected $casts = [
        'publication_year' => 'integer',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_category');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function bookStatistics()
    {
        return $this->hasOne(BookStatistics::class);
    }
}