<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // If the user is an admin, redirect to admin dashboard
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }
            
            // If the user is a regular user, show home page
            return view('home');
        }
        
        return view('home');
    }
}
