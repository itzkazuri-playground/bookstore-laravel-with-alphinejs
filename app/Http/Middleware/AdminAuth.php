<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin is logged in using session OR if they are authenticated as an admin user
        if (!session()->has('admin_logged_in')) {
            // Check if the user is authenticated and has admin role
            if (Auth::check() && Auth::user()->isAdmin()) {
                // User is authenticated as admin, allow access
                return $next($request);
            }
            
            // Redirect to admin login
            if ($request->is('admin/*')) {
                return redirect('/admin/login')->with('error', 'Please login to access admin panel');
            }
            
            return redirect('/admin/login')->with('error', 'Please login to access admin panel');
        }

        return $next($request);
    }
}