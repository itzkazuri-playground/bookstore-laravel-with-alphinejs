<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminCategoriesController extends BaseAdminController
{
    /**
     * Show admin categories page
     */
    public function index(Request $request): View
    {
        $query = Category::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
        }
        
        $categories = $query->paginate(15)->appends($request->query());
        
        return view('admin.categories.index', compact('categories'));
    }
    
    /**
     * Show category create form
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }
    
    /**
     * Store new category
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);
        
        Category::create($request->all());
        
        return redirect()->route('admin.categories')->with('success', 'Category created successfully.');
    }
    
    /**
     * Show category edit form
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }
    
    /**
     * Update category
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);
        
        $category->update($request->all());
        
        return redirect()->route('admin.categories')->with('success', 'Category updated successfully.');
    }
    
    /**
     * Delete category
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        
        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully.');
    }
}