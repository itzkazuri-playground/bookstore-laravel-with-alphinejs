<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Store a new rating
     */
    public function store(Request $request): JsonResponse
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Authentication required. Please log in to rate books.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $bookId = $request->book_id;
        $ratingValue = $request->rating;
        
        // For authenticated users, use their ID as identifier instead of name
        $voterIdentifier = auth()->user()->id;

        // Check if this user has already rated this specific book
        // $existingRating = Rating::where('book_id', $bookId)
        //                       ->where('voter_identifier', $voterIdentifier)
        //                       ->first();

        // if ($existingRating) {
        //     return response()->json([
        //         'message' => 'You have already rated this book'
        //     ], 409);
        // }

        // Verify that the book exists
        $book = Book::find($bookId);
        if (!$book) {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }

        // Store the rating
        DB::transaction(function () use ($bookId, $ratingValue, $voterIdentifier) {
            $rating = new Rating();
            $rating->book_id = $bookId;
            $rating->rating = $ratingValue;
            $rating->voter_identifier = $voterIdentifier; // Use user ID instead of name
            $rating->save();
        });

        return response()->json([
            'message' => 'Rating submitted successfully'
        ], 201);
    }
}
