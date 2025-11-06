@extends('layouts.app')

@section('title', 'Top Authors')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Top Authors</h1>
    
    <!-- Tab Navigation -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex space-x-8">
            <a 
                href="{{ route('authors.index', ['tab' => 'popularity', 'search' => request('search')]) }}"
                class="{{ request('tab') === 'popularity' || !request('tab') ? 'border-b-2 border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 text-sm font-medium"
            >
                By Popularity
            </a>
            <a 
                href="{{ route('authors.index', ['tab' => 'rating', 'search' => request('search')]) }}"
                class="{{ request('tab') === 'rating' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 text-sm font-medium"
            >
                By Average Rating
            </a>
            <a 
                href="{{ route('authors.index', ['tab' => 'trending', 'search' => request('search')]) }}"
                class="{{ request('tab') === 'trending' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 text-sm font-medium"
            >
                Trending
            </a>
        </nav>
    </div>
    
    <!-- Search Form -->
    <div class="mb-6">
        <form action="{{ route('authors.index') }}" method="GET" id="searchForm">
            <input type="hidden" name="tab" value="{{ request('tab', 'popularity') }}">
            <div class="flex">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search authors..." 
                    value="{{ request('search') }}"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <button 
                    type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-r-lg transition duration-200"
                >
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Tab Description -->
    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
        @switch(request('tab', 'popularity'))
            @case('rating')
                <p><strong>By Average Rating:</strong> Authors ranked by overall quality of their books based on average ratings.</p>
                @break
            @case('trending')
                <p><strong>Trending:</strong> Authors gaining momentum, comparing ratings from last 30 days versus previous 30 days.</p>
                @break
            @default
                <p><strong>By Popularity:</strong> Authors ranked by the number of ratings received (only ratings > 5 included).</p>
        @endswitch
    </div>

    <!-- Authors List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($authors as $author)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">
                        <a href="{{ route('authors.show', $author->id) }}" class="text-blue-600 hover:text-blue-800">
                            {{ $author->name }}
                        </a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                        {{ Str::limit($author->biography, 100) }}
                    </p>
                    
                    <!-- Author Stats -->
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Books:</span>
                            <span class="font-medium">{{ $author->books->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Avg Rating:</span>
                            <span class="font-medium">
                                {{ $author->authorStatistics ? number_format($author->authorStatistics->average_rating, 1) : 'N/A' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Ratings:</span>
                            <span class="font-medium">
                                {{ $author->authorStatistics ? $author->authorStatistics->total_ratings : 0 }}
                            </span>
                        </div>
                        
                        @if($author->bestRatedBook)
                        <div class="pt-2 border-t border-gray-200">
                            <div class="text-xs text-gray-500">Best Rated Book:</div>
                            <div class="text-xs font-medium truncate">{{ $author->bestRatedBook->title }}</div>
                        </div>
                        @endif
                        
                        @if($author->worstRatedBook)
                        <div>
                            <div class="text-xs text-gray-500">Worst Rated Book:</div>
                            <div class="text-xs font-medium truncate">{{ $author->worstRatedBook->title }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">No authors found.</p>
                @if(request('search'))
                    <p class="text-gray-400 mt-2">Try a different search term.</p>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $authors->links() }}
    </div>
</div>
@endsection