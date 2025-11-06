<?php

namespace App\Http\Controllers\Admin;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminBooksController extends BaseAdminController
{
    /**
     * Show admin books page
     */
    public function index(Request $request): View
    {
        $query = Book::with(['author', 'categories']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('isbn', 'LIKE', "%{$search}%")
                  ->orWhere('publisher', 'LIKE', "%{$search}%")
                  ->orWhereHas('author', function ($authorQuery) use ($search) {
                      $authorQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Filter by author
        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }
        
        // Filter by availability status
        if ($request->filled('availability_status')) {
            $query->where('availability_status', $request->availability_status);
        }
        
        // Filter by publication year range
        if ($request->filled('from_year') && $request->filled('to_year')) {
            $query->whereBetween('publication_year', [$request->from_year, $request->to_year]);
        } elseif ($request->filled('from_year')) {
            $query->where('publication_year', '>=', $request->from_year);
        } elseif ($request->filled('to_year')) {
            $query->where('publication_year', '<=', $request->to_year);
        }
        
        $books = $query->paginate(10)->appends($request->query());
        $authors = Author::orderBy('name')->get(); // For filter dropdown, sorted alphabetically
        $statuses = ['available', 'rented', 'reserved']; // For filter dropdown
        
        return view('admin.books', compact('books', 'authors', 'statuses'));
    }
    
    /**
     * Show book create form
     */
    public function create(): View
    {
        $authors = Author::orderBy('name')->get();
        $categories = Category::all();
        return view('admin.books.create', compact('authors', 'categories'));
    }
    
    /**
     * Store new book
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'isbn' => 'nullable|string|max:20',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 5),
            'availability_status' => 'required|in:available,rented,reserved',
        ]);
        
        Book::create($request->all());
        
        return redirect()->route('admin.books')->with('success', 'Book created successfully.');
    }
    
    /**
     * Show book edit form
     */
    public function edit(Book $book): View
    {
        $authors = Author::orderBy('name')->get();
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'authors', 'categories'));
    }
    
    /**
     * Update book
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'isbn' => 'nullable|string|max:20',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 5),
            'availability_status' => 'required|in:available,rented,reserved',
        ]);
        
        $book->update($request->all());
        
        return redirect()->route('admin.books')->with('success', 'Book updated successfully.');
    }
    
    /**
     * Delete book
     */
    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();
        
        return redirect()->route('admin.books')->with('success', 'Book deleted successfully.');
    }
}