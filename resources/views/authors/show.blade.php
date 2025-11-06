@extends('layouts.app')

@section('title', $author->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Author Header -->
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-3xl font-bold">{{ $author->name }}</h1>
            <p class="text-gray-600 mt-2">{{ $author->biography }}</p>
        </div>

        <!-- Author Stats -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <h3 class="text-2xl font-bold">{{ $totalBooks }}</h3>
                    <p class="text-gray-600">Books</p>
                </div>
                <div class="text-center">
                    <h3 class="text-2xl font-bold">{{ $totalRatings }}</h3>
                    <p class="text-gray-600">Total Ratings</p>
                </div>
                <div class="text-center">
                    <h3 class="text-2xl font-bold">{{ $overallAverageRating }}</h3>
                    <p class="text-gray-600">Overall Avg Rating</p>
                </div>
                <div class="text-center">
                    <h3 class="text-2xl font-bold">
                        {{ $author->authorStatistics ? number_format($author->authorStatistics->trending_score ?? 0, 2) : 'N/A' }}
                    </h3>
                    <p class="text-gray-600">Trending Score</p>
                </div>
            </div>
        </div>

        <!-- Best and Worst Rated Books -->
        @if($author->bestRatedBook || $author->worstRatedBook)
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-xl font-bold mb-4">Top Performers</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($author->bestRatedBook)
                <div class="border border-gray-200 rounded-lg p-4 bg-yellow-50">
                    <h3 class="font-bold text-lg mb-2 text-yellow-800">Best Rated Book</h3>
                    <p class="font-semibold">
                        <a href="{{ route('books.show', $author->bestRatedBook->id) }}" class="text-blue-600 hover:text-blue-800">
                            {{ $author->bestRatedBook->title }}
                        </a>
                    </p>
                    @if($author->bestRatedBook->bookStatistics)
                    <p class="text-sm text-gray-600">
                        Rating: {{ number_format($author->bestRatedBook->bookStatistics->average_rating, 1) }} 
                        ({{ $author->bestRatedBook->bookStatistics->total_ratings }} ratings)
                    </p>
                    @endif
                </div>
                @endif
                
                @if($author->worstRatedBook)
                <div class="border border-gray-200 rounded-lg p-4 bg-red-50">
                    <h3 class="font-bold text-lg mb-2 text-red-800">Worst Rated Book</h3>
                    <p class="font-semibold">
                        <a href="{{ route('books.show', $author->worstRatedBook->id) }}" class="text-blue-600 hover:text-blue-800">
                            {{ $author->worstRatedBook->title }}
                        </a>
                    </p>
                    @if($author->worstRatedBook->bookStatistics)
                    <p class="text-sm text-gray-600">
                        Rating: {{ number_format($author->worstRatedBook->bookStatistics->average_rating, 1) }} 
                        ({{ $author->worstRatedBook->bookStatistics->total_ratings }} ratings)
                    </p>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Books by Author -->
        <div class="p-6">
            <h2 class="text-2xl font-bold mb-6">All Books by {{ $author->name }}</h2>
            
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
                                    <a href="{{ route('books.show', $book->id) }}" class="text-blue-600 hover:text-blue-800">
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