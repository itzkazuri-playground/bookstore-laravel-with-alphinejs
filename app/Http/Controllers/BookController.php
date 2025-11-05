<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a single book with its details
     */
    public function show($id)
    {
        $book = Book::with(['author', 'categories'])->findOrFail($id);
        
        // Calculate average rating and total ratings for this book
        $ratingStats = Rating::where('book_id', $book->id)
                            ->selectRaw('AVG(rating) as average_rating, COUNT(*) as total_ratings')
                            ->first();
        
        $averageRating = $ratingStats->average_rating ? round($ratingStats->average_rating, 2) : 0;
        $totalRatings = $ratingStats->total_ratings;

        // Check if current user has already rated this book
        $userRating = null;
        if (Auth::check()) {
            $userRating = Rating::where('book_id', $book->id)
                               ->where('voter_identifier', Auth::user()->id)
                               ->first();
        }

        return view('books.show', compact('book', 'averageRating', 'totalRatings', 'userRating'));
    }

    /**
     * Store a rating for a specific book (AJAX request)
     */
    public function rateBook(Request $request, $bookId): JsonResponse
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Authentication required. Please log in to rate books.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $ratingValue = $request->rating;
        $userId = Auth::user()->id;

        // Check if this user has already rated this specific book
        $existingRating = Rating::where('book_id', $bookId)
                              ->where('voter_identifier', $userId)
                              ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->rating = $ratingValue;
            $existingRating->rated_at = now();
            $existingRating->save();
        } else {
            // Create new rating
            $rating = new Rating();
            $rating->book_id = $bookId;
            $rating->rating = $ratingValue;
            $rating->voter_identifier = $userId;
            $rating->save();
        }

        // Recalculate the average rating for the book
        $ratingStats = Rating::where('book_id', $bookId)
                            ->selectRaw('AVG(rating) as average_rating, COUNT(*) as total_ratings')
                            ->first();
        
        $averageRating = $ratingStats->average_rating ? round($ratingStats->average_rating, 2) : 0;
        $totalRatings = $ratingStats->total_ratings;

        return response()->json([
            'message' => 'Rating submitted successfully',
            'average_rating' => $averageRating,
            'total_ratings' => $totalRatings,
            'user_rating' => $ratingValue
        ], 201);
    }
}
