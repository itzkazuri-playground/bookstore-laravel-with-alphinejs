@extends('layouts.app')

@section('title', 'Top Authors')

@section('content')
<div x-data="authorsData()" class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">Top Authors</h1>
                
                <!-- Tabs for different rankings -->
                <div class="mb-6 border-b border-gray-200">
                    <nav class="flex space-x-8">
                        <button 
                            @click="activeTab = 'popularity'" 
                            :class="{ 'border-indigo-500 text-gray-900': activeTab === 'popularity', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'popularity' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        >
                            By Popularity
                        </button>
                        <button 
                            @click="activeTab = 'rating'" 
                            :class="{ 'border-indigo-500 text-gray-900': activeTab === 'rating', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'rating' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        >
                            By Average Rating
                        </button>
                        <button 
                            @click="activeTab = 'trending'" 
                            :class="{ 'border-indigo-500 text-gray-900': activeTab === 'trending', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'trending' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        >
                            Trending
                        </button>
                    </nav>
                </div>
                
                <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">
                        <strong>Popularity:</strong> Based on voters count (rating > 5 only)
                        <br>
                        <strong>By Average Rating:</strong> Overall quality based on average rating
                        <br>
                        <strong>Trending:</strong> Authors gaining momentum (comparing last 30 days vs previous 30 days)
                    </p>
                </div>
                
                <!-- Loading indicator -->
                <div x-show="loading" class="flex justify-center my-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                </div>
                
                <!-- Authors list -->
                <div x-show="!loading" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" x-text="getRankingHeaderText()"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Ratings</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Best Rated Book</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Worst Rated Book</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="(author, index) in authors" :key="author.id">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="pagination.from + index"></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900" x-text="author.name"></div>
                                        <div class="text-sm text-gray-500" x-text="author.country || 'N/A'"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span x-text="getRankingValue(author)"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="author.total_voters"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div x-show="author.best_rated_book" x-text="author.best_rated_book?.title || 'N/A'"></div>
                                        <div x-show="!author.best_rated_book" class="text-gray-400">N/A</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div x-show="author.worst_rated_book" x-text="author.worst_rated_book?.title || 'N/A'"></div>
                                        <div x-show="!author.worst_rated_book" class="text-gray-400">N/A</div>
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

    <script>
        function authorsData() {
            return {
                authors: [],
                activeTab: 'popularity',
                loading: false,
                pagination: {
                    current_page: 1,
                    last_page: 1,
                    per_page: 20,
                    total: 0,
                    from: 1,
                    to: 20
                },
                
                init() {
                    this.loadAuthors();
                },
                
                loadAuthors() {
                    this.loading = true;
                    
                    // Determine the endpoint based on active tab
                    let endpoint = '/api/authors';
                    if (this.activeTab === 'popularity') {
                        endpoint += '?sort=popularity';
                    } else if (this.activeTab === 'rating') {
                        endpoint += '?sort=rating';
                    } else if (this.activeTab === 'trending') {
                        endpoint += '?sort=trending';
                    }
                    
                    const params = new URLSearchParams({
                        page: this.pagination.current_page,
                        per_page: 20
                    });
                    
                    fetch(`${endpoint}&${params}`)
                        .then(response => response.json())
                        .then(data => {
                            this.authors = data.data || [];
                            this.pagination = {
                                current_page: data.current_page || 1,
                                last_page: data.last_page || 1,
                                per_page: data.per_page || 20,
                                total: data.total || 0,
                                from: data.from || 1,
                                to: data.to || 20
                            };
                        })
                        .catch(error => {
                            console.error('Error loading authors:', error);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },
                
                getRankingHeaderText() {
                    switch(this.activeTab) {
                        case 'popularity':
                            return 'Voters Count (>5 Rating)';
                        case 'rating':
                            return 'Average Rating';
                        case 'trending':
                            return 'Trending Score';
                        default:
                            return 'Value';
                    }
                },
                
                getRankingValue(author) {
                    switch(this.activeTab) {
                        case 'popularity':
                            return author.voters_above_5;
                        case 'rating':
                            return parseFloat(author.average_rating).toFixed(2);
                        case 'trending':
                            return parseFloat(author.trending_score).toFixed(2);
                        default:
                            return 0;
                    }
                },
                
                goToPage(page) {
                    if (page >= 1 && page <= this.pagination.last_page) {
                        this.pagination.current_page = page;
                        this.loadAuthors();
                    }
                },
                
                getPageNumbers() {
                    const pages = [];
                    const start = Math.max(1, this.pagination.current_page - 2);
                    const end = Math.min(this.pagination.last_page, this.pagination.current_page + 2);
                    
                    for (let i = start; i <= end; i++) {
                        pages.push(i);
                    }
                    
                    return pages;
                }
            }
        }
    </script>
</div>
@endsection