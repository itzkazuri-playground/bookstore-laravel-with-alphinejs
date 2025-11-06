<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserRatingController extends Controller
{
    /**
     * Delete a specific rating
     */
    public function destroy($id): JsonResponse
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Authentication required.'
            ], 401);
        }

        $rating = Rating::where('id', $id)
                       ->where('voter_identifier', Auth::user()->id)
                       ->first();

        if (!$rating) {
            return response()->json([
                'message' => 'Rating not found or you do not have permission to delete it.'
            ], 404);
        }

        $rating->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rating deleted successfully.'
        ]);
    }
}