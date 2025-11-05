<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // If the user is an admin, redirect to admin dashboard
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }
            
            // If the user is a regular user, redirect to home/dashboard
            return redirect()->intended('/dashboard');
        }

        return $next($request);
    }
}