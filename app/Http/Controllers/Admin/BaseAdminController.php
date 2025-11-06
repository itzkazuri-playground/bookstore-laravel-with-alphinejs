<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            $user = Auth::user();
            if (!$user || $user->role !== 'admin') {
                abort(403, 'Unauthorized access to admin section');
            }
            return $next($request);
        });
    }   
}
