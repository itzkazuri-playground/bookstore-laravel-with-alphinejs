<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    /**
     * Get paginated list of authors with statistics
     */
    public function index(Request $request): JsonResponse
    {
        $sort = $request->get('sort', 'popularity');
        $perPage = $request->get('per_page', 20);
        
        // Build the query with proper joins and sorting
        $query = Author::with(['authorStatistics', 'bestRatedBook', 'worstRatedBook'])
                      ->join('author_statistics', 'authors.id', '=', 'author_statistics.author_id');

        switch ($sort) {
            case 'rating':
                $query->orderBy('author_statistics.average_rating', 'desc');
                break;
            case 'trending':
                $query->orderBy('author_statistics.trending_score', 'desc');
                break;
            case 'popularity':
            default:
                $query->orderBy('author_statistics.voters_above_5', 'desc');
                break;
        }

        // Apply pagination - the join makes this work efficiently
        $authors = $query->select('authors.*')
                        ->paginate($perPage);

        // Transform the results to include properly formatted data
        $authors->getCollection()->transform(function ($author) {
            return [
                'id' => $author->id,
                'name' => $author->name,
                'country' => $author->country,
                'bio' => $author->bio,
                'total_voters' => $author->authorStatistics ? $author->authorStatistics->total_voters : 0,
                'voters_above_5' => $author->authorStatistics ? $author->authorStatistics->voters_above_5 : 0,
                'average_rating' => $author->authorStatistics ? number_format($author->authorStatistics->average_rating, 2) : 0,
                'trending_score' => $author->authorStatistics ? number_format($author->authorStatistics->trending_score, 2) : 0,
                'best_rated_book' => $author->bestRatedBook ? [
                    'id' => $author->bestRatedBook->id,
                    'title' => $author->bestRatedBook->title
                ] : null,
                'worst_rated_book' => $author->worstRatedBook ? [
                    'id' => $author->worstRatedBook->id,
                    'title' => $author->worstRatedBook->title
                ] : null
            ];
        });

        return response()->json($authors);
    }
}
