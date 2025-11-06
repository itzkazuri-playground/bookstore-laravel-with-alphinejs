<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin/*')) {
            // If not authenticated at all, redirect to login
            if (!Auth::check()) {
                return redirect()->guest(route('login'));
            }
            
            // If authenticated but not an admin, deny access
            if (!Auth::user()->isAdmin()) {
                return redirect('/')->with('error', 'You do not have permission to access this page.');
            }
        }

        return $next($request);
    }
}
