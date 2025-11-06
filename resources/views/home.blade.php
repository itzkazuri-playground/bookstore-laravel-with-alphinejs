@extends('layouts.app')

@section('title', 'Discover')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-xl overflow-hidden mb-12">
            <div class="px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
                <div class="lg:w-2/3">
                    <h1 class="text-4xl font-bold text-white mb-4">Discover Amazing Books</h1>
                    <p class="text-xl text-blue-100 mb-8">Explore our vast collection and find your next favorite read. Rate books and connect with fellow book lovers.</p>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('books.index') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-lg font-semibold text-center transition transform hover:-translate-y-0.5 shadow-lg">
                            Browse Books
                        </a>
                        <a href="{{ route('authors.index') }}" class="bg-transparent border-2 border-white text-white hover:bg-white/10 px-6 py-3 rounded-lg font-semibold text-center transition">
                            Top Authors
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ \App\Models\Book::count() }}</div>
                <div class="text-gray-600">Books Available</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <div class="text-3xl font-bold text-green-600 mb-2">{{ \App\Models\Author::count() }}</div>
                <div class="text-gray-600">Authors Featured</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <div class="text-3xl font-bold text-purple-600 mb-2">{{ \App\Models\Rating::count() }}</div>
                <div class="text-gray-600">Ratings Given</div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-12">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">What You Can Do Here</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 hover:shadow-lg transition duration-300 rounded-lg">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Browse Books</h3>
                    <p class="text-gray-600">Explore our diverse collection of books with advanced filters and search options.</p>
                </div>
                
                <div class="text-center p-6 hover:shadow-lg transition duration-300 rounded-lg">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Rate & Review</h3>
                    <p class="text-gray-600">Share your thoughts and experiences by rating books from 1 to 10.</p>
                </div>
                
                <div class="text-center p-6 hover:shadow-lg transition duration-300 rounded-lg">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Top Authors</h3>
                    <p class="text-gray-600">Discover the most popular authors based on community ratings and trends.</p>
                </div>
            </div>
        </div>

        @guest
        <!-- Guest CTA Section -->
        <div class="bg-gradient-to-r from-indigo-500 to-blue-600 rounded-2xl shadow-xl p-8 text-center mb-12">
            <h2 class="text-3xl font-bold text-white mb-4">Join Our Community</h2>
            <p class="text-xl text-blue-100 mb-6">Create an account to rate books, discover new reads, and connect with other book lovers.</p>
            <a href="{{ route('login') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg font-semibold inline-block transition transform hover:-translate-y-0.5 shadow-lg">
                Login or Register
            </a>
        </div>
        @endguest
    </div>
</div>
@endsection