<?php

namespace App\Http\Controllers\Admin;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminAuthorsController extends BaseAdminController
{
    /**
     * Show admin authors page
     */
    public function index(Request $request): View
    {
        $query = Author::with(['books']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('country', 'LIKE', "%{$search}%")
                  ->orWhere('bio', 'LIKE', "%{$search}%");
        }
        
        // Filter by country
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }
        
        $authors = $query->paginate(10)->appends($request->query());
        $countries = Author::select('country')->distinct()->orderBy('country')->pluck('country')->filter(); // For filter dropdown, sorted alphabetically
        
        return view('admin.authors', compact('authors', 'countries'));
    }
    
    /**
     * Show author create form
     */
    public function create(): View
    {
        return view('admin.authors.create');
    }
    
    /**
     * Store new author
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
        ]);
        
        Author::create($request->all());
        
        return redirect()->route('admin.authors')->with('success', 'Author created successfully.');
    }
    
    /**
     * Show author edit form
     */
    public function edit(Author $author): View
    {
        return view('admin.authors.edit', compact('author'));
    }
    
    /**
     * Update author
     */
    public function update(Request $request, Author $author): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
        ]);
        
        $author->update($request->all());
        
        return redirect()->route('admin.authors')->with('success', 'Author updated successfully.');
    }
    
    /**
     * Delete author
     */
    public function destroy(Author $author): RedirectResponse
    {
        $author->delete();
        
        return redirect()->route('admin.authors')->with('success', 'Author deleted successfully.');
    }
}