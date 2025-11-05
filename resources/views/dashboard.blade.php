@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-3xl font-bold mb-6">Welcome to Your Dashboard</h1>
                <p class="mb-6">Hello, {{ Auth::user()->name }}! This is your personal dashboard where you can manage your account and activities.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold mb-2">Your Account</h3>
                        <p>Manage your profile details, password, and other account settings.</p>
                        <a href="{{ route('profile.edit') }}" class="inline-block mt-3 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                            Manage Profile
                        </a>
                    </div>
                    
                    <div class="bg-green-50 p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold mb-2">Rate Books</h3>
                        <p>Give ratings to books you've read and help us provide better recommendations.</p>
                        <a href="{{ route('ratings.create') }}" class="inline-block mt-3 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                            Rate a Book
                        </a>
                    </div>
                    
                    <div class="bg-yellow-50 p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold mb-2">Browse Books</h3>
                        <p>Explore our extensive collection of books with various filters and search options.</p>
                        <a href="{{ route('books.index') }}" class="inline-block mt-3 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                            Browse Books
                        </a>
                    </div>
                    
                    <div class="bg-purple-50 p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold mb-2">Top Authors</h3>
                        <p>Discover the most popular authors based on community ratings.</p>
                        <a href="{{ route('authors.index') }}" class="inline-block mt-3 bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded">
                            See Top Authors
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection