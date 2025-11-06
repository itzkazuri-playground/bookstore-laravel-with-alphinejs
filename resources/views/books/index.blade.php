@extends('layouts.app')

@section('title', 'Books')

@section('content')
<div x-data="booksData()" class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">Book Collection</h1>
                
                <!-- Filters -->
                <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input 
                                type="text" 
                                x-model="searchQuery" 
                                @input.debounce.300ms="loadBooks" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                placeholder="Title, Author, ISBN..."
                            />
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                            <select 
                                x-model="filters.author_id" 
                                @change="loadBooks" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            >
                                <option value="">All Authors</option>
                                <template x-for="author in authors" :key="author.id">
                                    <option :value="author.id" x-text="author.name"></option>
                                </template>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Publication Year</label>
                            <div class="flex space-x-2">
                                <input 
                                    type="number" 
                                    x-model="filters.from_year" 
                                    @input.debounce.300ms="loadBooks" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="From"
                                />
                                <input 
                                    type="number" 
                                    x-model="filters.to_year" 
                                    @input.debounce.300ms="loadBooks" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="To"
                                />
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                            <div class="flex space-x-2">
                                <input 
                                    type="number" 
                                    min="1" 
                                    max="10" 
                                    x-model="filters.min_rating" 
                                    @input.debounce.300ms="loadBooks" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="Min"
                                />
                                <input 
                                    type="number" 
                                    min="1" 
                                    max="10" 
                                    x-model="filters.max_rating" 
                                    @input.debounce.300ms="loadBooks" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="Max"
                                />
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Categories</label>
                        <div class="relative">
                            <div 
                                @click="showCategoryDropdown = !showCategoryDropdown; if(!showCategoryDropdown) filterCategorySearch = ''"
                                class="w-full p-2 border border-gray-300 rounded-md shadow-sm cursor-pointer bg-white min-h-12"
                            >
                                <div class="flex flex-wrap gap-1 items-center">
                                    <template x-for="selectedCategoryId in filters.categories" :key="selectedCategoryId">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <span x-text="getCategoryName(selectedCategoryId)"></span>
                                            <button 
                                                @click.stop="removeCategory(selectedCategoryId)" 
                                                class="ml-1 text-indigo-600 hover:text-indigo-900 focus:outline-none"
                                            >
                                                <span class="sr-only">Remove</span>
                                                &times;
                                            </button>
                                        </span>
                                    </template>
                                    <input 
                                        x-ref="categorySearchInput"
                                        type="text" 
                                        placeholder="Search categories..." 
                                        class="flex-grow outline-none border-none focus:ring-0 p-1 min-w-[120px]"
                                        @click.stop
                                        @input="filterCategorySearch = $event.target.value"
                                        @keydown.delete="handleInputDelete"
                                        :value="filterCategorySearch"
                                        x-show="showCategoryDropdown"
                                    />
                                    <input 
                                        type="text" 
                                        placeholder="Search categories..." 
                                        class="flex-grow outline-none border-none focus:ring-0 p-1 min-w-[120px]"
                                        @click="showCategoryDropdown = true; $nextTick(() => $refs.categorySearchInput?.focus())"
                                        x-show="!showCategoryDropdown"
                                        readonly
                                    />
                                </div>
                            </div>
                            
                            <div 
                                x-show="showCategoryDropdown" 
                                @click.away="showCategoryDropdown = false; filterCategorySearch = ''"
                                class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base overflow-auto focus:outline-none sm:text-sm border border-gray-200"
                            >
                                <template x-if="filteredCategories.length === 0 && filterCategorySearch">
                                    <div class="py-2 px-4 text-gray-500">
                                        No categories match "<span x-text="filterCategorySearch"></span>"
                                        <a href="#" @click.prevent="addNewCategory(filterCategorySearch)" class="ml-2 text-indigo-600 hover:text-indigo-900">Add as new category?</a>
                                    </div>
                                </template>
                                <template x-if="filteredCategories.length > 0">
                                    <template x-for="category in filteredCategories" :key="category.id">
                                        <div 
                                            class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-50"
                                            @click="toggleCategory(category.id); $nextTick(() => $refs.categorySearchInput?.focus())"
                                        >
                                            <div class="flex items-center">
                                                <span 
                                                    :class="{'font-semibold': isCategorySelected(category.id)}"
                                                    x-text="category.name"
                                                    class="block font-normal"
                                                ></span>
                                            </div>
                                            <div 
                                                x-show="isCategorySelected(category.id)" 
                                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600"
                                            >
                                                ✓
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                        <select 
                            x-model="sortBy" 
                            @change="loadBooks" 
                            class="w-full md:w-1/4 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                            <option value="average_rating">Average Rating</option>
                            <option value="total_voters">Total Voters</option>
                            <option value="recent_popularity">Recent Popularity</option>
                            <option value="title">Title (A-Z)</option>
                        </select>
                    </div>
                </div>
                
                <!-- Loading indicator -->
                <div x-show="loading" class="flex justify-center my-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                </div>
                
                <!-- Books list -->
                <div x-show="!loading" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categories</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ISBN</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voters</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="book in books" :key="book.id">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a :href="'/books/' + book.id" class="text-sm font-medium text-indigo-600 hover:text-indigo-900" x-text="book.title"></a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900" x-text="book.author_name"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <template x-for="category in book.categories" :key="category.id">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-1" x-text="category.name"></span>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="book.isbn"></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><span x-text="book.average_rating"></span>/10</div>
                                        <div x-show="book.rating_trend === 'up'" class="text-xs text-green-600">↑ Trending</div>
                                        <div x-show="book.rating_trend === 'down'" class="text-xs text-red-600">↓ Declining</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="book.total_voters"></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="{
                                            'bg-green-100 text-green-800': book.availability_status === 'available',
                                            'bg-yellow-100 text-yellow-800': book.availability_status === 'rented',
                                            'bg-red-100 text-red-800': book.availability_status === 'reserved'
                                        }" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" x-text="book.availability_status"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing <span x-text="pagination.from"></span> to <span x-text="pagination.to"></span> of <span x-text="pagination.total"></span> results
                        </div>
                        <div class="flex space-x-2">
                            <button 
                                @click="goToPage(pagination.current_page - 1)" 
                                :disabled="pagination.current_page <= 1"
                                :class="{'opacity-50 cursor-not-allowed': pagination.current_page <= 1}"
                                class="px-3 py-1 rounded-md bg-white border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Previous
                            </button>
                            
                            <template x-for="page in getPageNumbers()" :key="page">
                                <button 
                                    @click="goToPage(page)" 
                                    :class="{'bg-indigo-500 text-white': page === pagination.current_page, 'bg-white text-gray-700 hover:bg-gray-50': page !== pagination.current_page}"
                                    class="px-3 py-1 rounded-md border border-gray-300 text-sm font-medium"
                                    x-text="page"
                                ></button>
                            </template>
                            
                            <button 
                                @click="goToPage(pagination.current_page + 1)" 
                                :disabled="pagination.current_page >= pagination.last_page"
                                :class="{'opacity-50 cursor-not-allowed': pagination.current_page >= pagination.last_page}"
                                class="px-3 py-1 rounded-md bg-white border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection