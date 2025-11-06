@extends('layouts.admin')

@section('title', $author->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Author Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $author->name }}</h1>
                    <p class="text-gray-600 mt-2">{{ $author->bio }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.authors.edit', $author) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Edit
                    </a>
                    <a href="{{ route('admin.authors') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Author Stats -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalBooks }}</h3>
                    <p class="text-gray-600">Books</p>
                </div>
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalRatings }}</h3>
                    <p class="text-gray-600">Total Ratings</p>
                </div>
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-gray-800">{{ number_format($averageRating, 2) }}</h3>
                    <p class="text-gray-600">Avg Rating</p>
                </div>
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-gray-800">{{ number_format($trendingScore, 2) }}</h3>
                    <p class="text-gray-600">Trending Score</p>
                </div>
            </div>
        </div>

        <!-- Author Information -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold mb-4">Author Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p><span class="font-medium">ID:</span> {{ $author->id }}</p>
                    <p><span class="font-medium">Name:</span> {{ $author->name }}</p>
                    @if($author->country)
                        <p><span class="font-medium">Country:</span> {{ $author->country }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Books by Author -->
        <div class="p-6">
            <h2 class="text-2xl font-bold mb-6">Books by {{ $author->name }}</h2>
            
            @if($books->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($books as $book)
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition duration-200">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="bg-gray-200 h-48 flex items-center justify-center">
                                    <span class="text-gray-500">No Cover Image</span>
                                </div>
                            @endif
                            
                            <div class="p-4">
                                <h3 class="font-bold text-lg mb-1">
                                    <a href="{{ route('admin.books.edit', $book) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $book->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-2">Published: {{ $book->publication_year }}</p>
                                
                                <!-- Categories -->
                                @if($book->categories->count() > 0)
                                <div class="mb-2">
                                    @foreach($book->categories->take(2) as $category)
                                        <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-xs font-semibold text-gray-700 mr-2 mb-1">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                    @if($book->categories->count() > 2)
                                        <span class="text-xs text-gray-500">+{{ $book->categories->count() - 2 }} more</span>
                                    @endif
                                </div>
                                @endif
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-blue-600">
                                        ${{ number_format($book->price, 2) }}
                                    </span>
                                    
                                    <div class="flex items-center">
                                        @if($book->bookStatistics && $book->bookStatistics->average_rating > 0)
                                            <span class="text-yellow-500 mr-1">★</span>
                                            <span>{{ number_format($book->bookStatistics->average_rating, 1) }}</span>
                                            <span class="text-gray-500 text-sm ml-1">({{ $book->bookStatistics->total_ratings }})</span>
                                        @else
                                            <span class="text-gray-400">No ratings</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8">
                    {{ $books->links() }}
                </div>
            @else
                <p class="text-gray-500">No books found for this author.</p>
            @endif
        </div>
    </div>
</div>
@endsection