<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard(): View
    {
        // Count statistics for dashboard
        $booksCount = Book::count();
        $authorsCount = Author::count();
        $categoriesCount = Category::count();
        $ratingsCount = Rating::count();
        
        // Get the authenticated admin user
        $adminUser = auth()->user();

        return view('admin.dashboard', compact('booksCount', 'authorsCount', 'categoriesCount', 'ratingsCount', 'adminUser'));
    }

    /**
     * Logout admin
     */
    public function logout()
    {
        // Clear both session and auth if needed
        session()->forget('admin_logged_in');
        Auth::logout();
        
        return redirect('/login');
    }
}