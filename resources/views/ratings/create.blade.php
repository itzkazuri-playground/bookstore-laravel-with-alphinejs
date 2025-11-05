@extends('layouts.app')

@section('content')
<div x-data="ratingData" class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">Rate a Book</h1>
                
                <form @submit.prevent="submitRating" class="space-y-6">
                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="author-search" 
                                x-model="searchTerm"
                                @input.debounce.300ms="searchAuthors" 
                                @focus="showAuthorDropdown = true"
                                @keydown.arrow-down.prevent="highlightNextAuthor"
                                @keydown.arrow-up.prevent="highlightPrevAuthor"
                                @keydown.enter.prevent="selectHighlightedAuthor"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                placeholder="Search for an author..."
                            />
                            
                            <div x-show="showAuthorDropdown && filteredAuthors.length" 
                                 class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                <template x-for="(author, index) in filteredAuthors" :key="author.id">
                                    <div 
                                        @click="selectAuthor(author)" 
                                        :class="{'bg-indigo-600 text-white': highlightedAuthorIndex === index, 'text-gray-900': highlightedAuthorIndex !== index}"
                                        class="relative cursor-default select-none py-2 pl-3 pr-9 hover:bg-indigo-500 hover:text-white"
                                        @mouseenter="highlightedAuthorIndex = index"
                                    >
                                        <span x-text="author.name" class="font-normal block truncate"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <input type="hidden" x-model="form.author_id" />
                    </div>
                    
                    <div>
                        <label for="book" class="block text-sm font-medium text-gray-700 mb-1">Book</label>
                        <select 
                            id="book" 
                            x-model="form.book_id" 
                            required
                            :disabled="!form.author_id || books.length === 0"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                            <option value="">Select a book</option>
                            <template x-for="book in books" :key="book.id">
                                <option :value="book.id" x-text="book.title"></option>
                            </template>
                        </select>
                        <p x-show="!form.author_id" class="mt-1 text-sm text-gray-500">Please select an author first</p>
                        <p x-show="form.author_id && books.length === 0" class="mt-1 text-sm text-gray-500">No books found for this author</p>
                    </div>
                    
                    <div>
                        <label for="voter_name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                        <input
                            type="text"
                            id="voter_name"
                            x-model="form.voter_name"
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="Enter your name"
                        />
                    </div>
                    
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating (1-10)</label>
                        <div class="flex items-center space-x-1">
                            <template x-for="starIndex in 10" :key="starIndex">
                                <button 
                                    type="button"
                                    @click="form.rating = starIndex"
                                    @mouseover="hoverRating = starIndex"
                                    @mouseleave="hoverRating = null"
                                    class="text-gray-300 focus:outline-none"
                                    :class="{
                                        'text-yellow-400': (hoverRating || form.rating) >= starIndex,
                                        'text-gray-300': (hoverRating || form.rating) < starIndex
                                    }"
                                >
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </button>
                            </template>
                            <span class="ml-4 text-lg font-bold text-gray-700" x-text="form.rating + ' / 10'"></span>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <button 
                            type="submit" 
                            :disabled="submitting"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                        >
                            <span x-show="submitting" class="mr-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            <span x-text="submitting ? 'Submitting...' : 'Submit Rating'"></span>
                        </button>
                    </div>
                    
                    <!-- Success/Error messages -->
                    <div x-show="message" :class="messageType === 'success' ? 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded' : 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded'">
                        <span x-text="message"></span>
                        <button x-show="messageType === 'success'" type="button" @click="goHome" class="ml-4 inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-200 hover:bg-green-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Go Home</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@vite('resources/js/pages/rating.js')
@endsection