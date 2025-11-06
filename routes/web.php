<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\AdminBooksController;
use App\Http\Controllers\Admin\AdminAuthorsController;
use App\Http\Controllers\Admin\AdminRatingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/books', function () {
    return view('books.index');
})->name('books.index');

// Single book detail page
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

// API route for rating a book
Route::post('/api/books/{bookId}/rate', [BookController::class, 'rateBook'])->middleware(['auth'])->name('books.rate');

Route::get('/authors', function () {
    return view('authors.index');
})->name('authors.index');
Route::get('/ratings/create', function () {
    return view('ratings.create');
})->name('ratings.create')->middleware(['auth']);

Route::get('/test', function () {
    return view('test');
})->name('test');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API routes for autocomplete
Route::get('/api/search/books', [SearchController::class, 'searchBooks'])->middleware(['admin.auth']);
Route::get('/api/search/authors', [SearchController::class, 'searchAuthors'])->middleware(['admin.auth']);

// Admin routes (only accessible by authenticated admin users)
Route::prefix('admin')->group(function () {
    // Remove the separate admin login route since we're using a single login
    // Admin login is now handled through the main login page
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    
    Route::middleware(['admin.auth'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Books routes
        Route::get('/books', [AdminBooksController::class, 'index'])->name('admin.books');
        Route::get('/books/create', [AdminBooksController::class, 'create'])->name('admin.books.create');
        Route::post('/books', [AdminBooksController::class, 'store'])->name('admin.books.store');
        Route::get('/books/{book}/edit', [AdminBooksController::class, 'edit'])->name('admin.books.edit');
        Route::put('/books/{book}', [AdminBooksController::class, 'update'])->name('admin.books.update');
        Route::delete('/books/{book}', [AdminBooksController::class, 'destroy'])->name('admin.books.destroy');
        
        // Authors routes
        Route::get('/authors', [AdminAuthorsController::class, 'index'])->name('admin.authors');
        Route::get('/authors/create', [AdminAuthorsController::class, 'create'])->name('admin.authors.create');
        Route::post('/authors', [AdminAuthorsController::class, 'store'])->name('admin.authors.store');
        Route::get('/authors/{author}/edit', [AdminAuthorsController::class, 'edit'])->name('admin.authors.edit');
        Route::put('/authors/{author}', [AdminAuthorsController::class, 'update'])->name('admin.authors.update');
        Route::delete('/authors/{author}', [AdminAuthorsController::class, 'destroy'])->name('admin.authors.destroy');
        
        // Ratings routes
        Route::get('/ratings', [AdminRatingsController::class, 'index'])->name('admin.ratings');
        Route::delete('/ratings/{rating}', [AdminRatingsController::class, 'destroy'])->name('admin.ratings.destroy');
    });
});

require __DIR__.'/auth.php';
