<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;

class SearchController extends Controller
{
    /**
     * Search for books by title
     */
    public function searchBooks(Request $request)
    {
        $query = $request->input('q');
        
        $books = Book::where('title', 'LIKE', "%{$query}%")
                    ->with('author')
                    ->limit(10)
                    ->get(['id', 'title', 'author_id']);
        
        return response()->json($books->map(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author ? $book->author->name : 'Unknown'
            ];
        }));
    }
    
    /**
     * Search for authors by name
     */
    public function searchAuthors(Request $request)
    {
        $query = $request->input('q');
        
        $authors = Author::where('name', 'LIKE', "%{$query}%")
                        ->limit(10)
                        ->get(['id', 'name']);
        
        return response()->json($authors->map(function ($author) {
            return [
                'id' => $author->id,
                'name' => $author->name
            ];
        }));
    }
}