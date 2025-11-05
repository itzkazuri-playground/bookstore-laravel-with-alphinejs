<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\RatingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes (no authentication required for visitors)
Route::get('/books', [BookController::class, 'index']);
Route::get('/categories', [BookController::class, 'categories']);
Route::get('/authors/dropdown', [BookController::class, 'authors']);  // For dropdown data
Route::get('/authors', [AuthorController::class, 'index']);           // For top authors listing

// Ratings API route - authentication handled within the controller
Route::post('/ratings', [RatingController::class, 'store']);

// Authenticated routes (for admin functionality if needed)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
