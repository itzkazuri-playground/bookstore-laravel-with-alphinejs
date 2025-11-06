<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    /**
     * Display a listing of authors with search functionality and multiple ranking tabs.
     */
    public function index(Request $request)
    {
        $query = $request->get('search');
        $tab = $request->get('tab', 'popularity'); // Default to popularity

        // Optimized query using joins with author_statistics table
        $authorsQuery = Author::leftJoin('author_statistics', 'authors.id', '=', 'author_statistics.author_id')
            ->select('authors.*');

        // Apply search filter
        if ($query) {
            $authorsQuery->where('authors.name', 'LIKE', "%{$query}%");
        }

        // Apply different ordering based on the selected tab using author_statistics table
        switch ($tab) {
            case 'popularity':
                // By Popularity: Voters count (rating > 5 only) - use voters_above_5 from author_statistics
                $authorsQuery->orderByRaw('COALESCE(author_statistics.voters_above_5, 0) DESC');
                break;
                
            case 'rating':
                // By Average Rating: Overall quality - use average_rating from author_statistics
                $authorsQuery->orderByRaw('COALESCE(author_statistics.average_rating, 0) DESC');
                break;
                
            case 'trending':
                // By Trending: Use trending_score from author_statistics
                $authorsQuery->orderByRaw('COALESCE(author_statistics.trending_score, 0) DESC');
                break;
                
            default:
                // Default to popularity
                $authorsQuery->orderByRaw('COALESCE(author_statistics.voters_above_5, 0) DESC');
                break;
        }

        $authors = $authorsQuery->with(['authorStatistics', 'bestRatedBook', 'worstRatedBook'])->paginate(20);

        return view('authors.index', compact('authors', 'query', 'tab'));
    }

    /**
     * Show the detail page for a specific author.
     */
    public function show($id)
    {
        $author = Author::with(['books.ratings'])->findOrFail($id);

        // Get all books by this author with their average ratings - using paginate to fix the links issue
        $books = $author->books()->with(['categories', 'bookStatistics'])->paginate(12);

        // Calculate overall stats for the author
        $totalBooks = $author->books()->count(); // Get total count separately
        $totalRatings = 0;
        $totalRatingSum = 0;

        foreach ($books as $book) {
            if ($book->bookStatistics) {
                $totalRatings += $book->bookStatistics->total_ratings;
                $totalRatingSum += $book->bookStatistics->total_ratings * $book->bookStatistics->average_rating;
            }
        }

        $overallAverageRating = $totalRatings > 0 ? number_format($totalRatingSum / $totalRatings, 2) : 0;

        return view('authors.show', compact('author', 'books', 'totalBooks', 'overallAverageRating', 'totalRatings'));
    }
}