@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-3xl font-bold mb-6">Welcome to Our Bookstore</h1>
                <p class="mb-6">Discover and rate books from our extensive collection. Find the most popular books and authors based on community ratings.</p>
                
                @guest
                <!-- Single login for all users -->
                <div class="bg-blue-50 p-6 rounded-lg shadow text-center mb-8">
                    <h3 class="text-xl font-semibold mb-4">Login to Your Account</h3>
                    <p class="mb-4">Access the bookstore as a user or admin with the same login page. You will be redirected based on your role after login.</p>
                    <a href="{{ route('login') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                        Login
                    </a>
                </div>
                @endguest
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('books.index') }}" class="bg-blue-100 p-6 rounded-lg shadow text-center hover:bg-blue-200 transition">
                        <h3 class="text-xl font-semibold mb-2">Browse Books</h3>
                        <p>Explore our collection of books with filters and search options</p>
                    </a>
                    
                    <a href="{{ route('authors.index') }}" class="bg-green-100 p-6 rounded-lg shadow text-center hover:bg-green-200 transition">
                        <h3 class="text-xl font-semibold mb-2">Top Authors</h3>
                        <p>See the most popular authors based on ratings and reviews</p>
                    </a>
                    
                    <a href="{{ route('ratings.create') }}" class="bg-yellow-100 p-6 rounded-lg shadow text-center hover:bg-yellow-200 transition">
                        <h3 class="text-xl font-semibold mb-2">Rate Books</h3>
                        <p>Give your opinion about books you've read</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection