<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BookController extends Controller
{
    /**
     * Get paginated list of books with filters
     */
    public function index(Request $request): JsonResponse
    {
        // Start with the main query
        $query = Book::with(['author', 'categories', 'bookStatistics']);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhereHas('author', function ($authorQuery) use ($search) {
                      $authorQuery->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhere('isbn', 'LIKE', "%{$search}%")
                  ->orWhere('publisher', 'LIKE', "%{$search}%");
            });
        }

        // Apply author filter
        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        // Apply year range filter
        if ($request->filled('from_year')) {
            $query->where('publication_year', '>=', $request->from_year);
        }
        if ($request->filled('to_year')) {
            $query->where('publication_year', '<=', $request->to_year);
        }

        // Apply availability status filter
        if ($request->filled('availability_status')) {
            $query->where('availability_status', $request->availability_status);
        }

        // Apply category filters
        if ($request->filled('categories')) {
            $categoryIds = explode(',', $request->categories);
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        // Get paginated results before applying rating-based sorting/filters
        /** @var LengthAwarePaginator $books */
        $books = $query->paginate($request->get('per_page', 15));

        // Apply rating filters after getting the paginated books
        /** @var Collection $filteredBooks */
        $filteredBooks = $books->getCollection()->filter(function ($book) use ($request) {
            $stats = $book->bookStatistics;
            if (!$stats) {
                return true; // Include books without statistics if no rating filters applied
            }
            
            // Apply rating range filters
            if ($request->filled('min_rating') && $stats->average_rating < $request->min_rating) {
                return false;
            }
            if ($request->filled('max_rating') && $stats->average_rating > $request->max_rating) {
                return false;
            }
            
            return true;
        });

        // Apply sorting
        $sortBy = $request->get('sort_by', 'average_rating');
        switch ($sortBy) {
            case 'total_voters':
                $filteredBooks = $filteredBooks->sortByDesc(function ($book) {
                    return $book->bookStatistics ? $book->bookStatistics->total_voters : 0;
                });
                break;
            case 'recent_popularity':
                $filteredBooks = $filteredBooks->sortByDesc(function ($book) {
                    return $book->bookStatistics ? $book->bookStatistics->last_30_days_avg : 0;
                });
                break;
            case 'title':
                $filteredBooks = $filteredBooks->sortBy('title');
                break;
            case 'average_rating':
            default:
                $filteredBooks = $filteredBooks->sortByDesc(function ($book) {
                    return $book->bookStatistics ? $book->bookStatistics->average_rating : 0;
                });
                break;
        }

        // Update the paginator with the sorted/filtered collection
        $books->setCollection($filteredBooks->values());

        // Map the results to include related data properly formatted
        $books->getCollection()->transform(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'author_name' => $book->author->name ?? 'Unknown',
                'categories' => $book->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name
                    ];
                })->toArray(),
                'isbn' => $book->isbn,
                'publisher' => $book->publisher,
                'publication_year' => $book->publication_year,
                'availability_status' => $book->availability_status,
                'average_rating' => $book->bookStatistics ? number_format($book->bookStatistics->average_rating, 2) : 0,
                'total_voters' => $book->bookStatistics ? $book->bookStatistics->total_voters : 0,
                'rating_trend' => $this->getRatingTrend($book->bookStatistics)
            ];
        });

        return response()->json($books);
    }

    /**
     * Get all categories for filter dropdown
     */
    public function categories(): JsonResponse
    {
        $categories = Category::select('id', 'name')->get();
        return response()->json(['data' => $categories]);
    }

    /**
     * Get all authors for filter dropdown
     */
    public function authors(): JsonResponse
    {
        $authors = Author::select('id', 'name')->get();
        return response()->json(['data' => $authors]);
    }

    /**
     * Get rating trend indicator for a book
     */
    private function getRatingTrend($statistics)
    {
        if (!$statistics) {
            return null;
        }

        if ($statistics->last_7_days_avg && $statistics->average_rating) {
            if ($statistics->last_7_days_avg > $statistics->average_rating * 1.1) {
                return 'up';
            } elseif ($statistics->last_7_days_avg < $statistics->average_rating * 0.9) {
                return 'down';
            }
        }

        return null;
    }
}