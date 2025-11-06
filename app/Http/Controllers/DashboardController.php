<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the user's ratings with book information
        $ratings = Rating::where('voter_identifier', Auth::user()->email)
                        ->with(['book' => function($query) {
                            $query->with(['author', 'categories']);
                        }])
                        ->latest()
                        ->paginate(10);
        
        return view('dashboard', compact('ratings'));
    }
}