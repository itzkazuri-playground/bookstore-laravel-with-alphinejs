@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div id="book-data" 
     data-book-id="{{ $book->id }}" 
     data-average-rating="{{ $averageRating }}"
     data-total-ratings="{{ $totalRatings }}"
     data-user-rating="{{ $userRating ? $userRating->rating : 0 }}"
     x-data="bookData()" class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/3 mb-6 md:mb-0 md:pr-6">
                        <!-- Book cover or placeholder -->
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-96 flex items-center justify-center">
                            <span class="text-gray-500">Book Cover</span>
                        </div>
                    </div>
                    
                    <div class="md:w-2/3">
                        <h1 class="text-3xl font-bold mb-2">{{ $book->title }}</h1>
                        <p class="text-lg text-gray-700 mb-4">by <span class="font-semibold">{{ $book->author->name }}</span></p>
                        
                        <!-- Rating display -->
                        <div class="mb-4">
                            <div class="flex items-center">
                                <div class="flex text-yellow-400">
                                    <template x-for="starIndex in 10" :key="starIndex">
                                        <span :class="starIndex <= averageRating ? 'text-yellow-400' : 'text-gray-300'">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </span>
                                    </template>
                                </div>
                                <span class="ml-2 font-semibold" x-text="averageRating + ' / 10 (' + totalRatings + ' ratings)'"></span>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold mb-2">Book Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                <div><span class="font-medium">ISBN:</span> {{ $book->isbn }}</div>
                                <div><span class="font-medium">Publisher:</span> {{ $book->publisher }}</div>
                                <div><span class="font-medium">Publication Year:</span> {{ $book->publication_year }}</div>
                                <div><span class="font-medium">Availability:</span> 
                                    <span class="px-2 py-1 rounded text-white text-xs"
                                          x-data="{ status: '{{ $book->availability_status }}' }"
                                          :class="availabilityStatusClass(status)">
                                        {{ ucfirst($book->availability_status) }}
                                    </span>
                                </div>
                                <div><span class="font-medium">Store Location:</span> {{ $book->store_location }}</div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold mb-2">Description</h3>
                            <p class="text-gray-700">{{ $book->description }}</p>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold mb-2">Categories</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($book->categories as $category)
                                    <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Rating section for authenticated users -->
                        @auth
                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-xl font-semibold mb-4">Rate this Book</h3>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating:</label>
                                <div class="flex items-center space-x-1">
                                    <template x-for="starIndex in 10" :key="starIndex">
                                        <button 
                                            type="button"
                                            @click="currentRating = starIndex"
                                            @mouseover="hoverRating = starIndex"
                                            @mouseleave="hoverRating = currentRating"
                                            class="text-gray-300 focus:outline-none"
                                            :class="{
                                                'text-yellow-400': (hoverRating || currentRating) >= starIndex,
                                                'text-gray-300': (hoverRating || currentRating) < starIndex
                                            }"
                                        >
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </button>
                                    </template>
                                    <span class="ml-4 text-lg font-bold text-gray-700" x-text="currentRating + ' / 10'"></span>
                                </div>
                            </div>
                            
                            <div>
                                <button 
                                    @click="submitRating()"
                                    :disabled="submitting"
                                    class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                                >
                                    <span x-show="submitting" class="mr-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                    <span x-text="submitting ? 'Submitting...' : 'Submit Rating'"></span>
                                </button>
                            </div>
                            
                            <!-- Success/Error messages -->
                            <div x-show="message" :class="messageType === 'success' ? 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-4' : 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4'">
                                <span x-text="message"></span>
                            </div>
                        </div>
                        @else
                        <div class="mt-8 border-t pt-6">
                            <p class="text-gray-700">Please <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">log in</a> to rate this book.</p>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@vite('resources/js/pages/book-detail.js')
@endsection