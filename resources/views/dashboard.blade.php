@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    @vite('resources/css/pages/dashboard.css')
@endpush

@section('content')
<div x-data="ratingHistory()" class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">Hello, {{ Auth::user()->name }}!</h1>
                    <p class="text-xl mt-2">Manage your account and book ratings</p>
                </div>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-2 bg-white/20 hover:bg-white/30 p-3 rounded-full transition">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM2 10a8 8 0 1116 0 8 8 0 01-16 0z" />
                        </svg>
                    </button>
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating History Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Your Rating History</h2>
                
                @if($ratings && $ratings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Your Rating</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Rated</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($ratings as $rating)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $rating->book->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $rating->book->author->name ?? 'Unknown' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 10; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461c.969 0 1.371-1.24.588-1.81l-2.8-2.034z" />
                                                </svg>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-500">{{ $rating->rating }}/10</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $rating->rated_at ? $rating->rated_at->format('M d, Y') : $rating->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('books.show', $rating->book->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        <button @click="openDeleteModal({{ $rating->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $ratings->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No ratings yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Start rating books to see your history here.</p>
                        <div class="mt-6">
                            <a href="{{ route('books.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Browse Books
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="isDeleteModalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900" id="modal-title">Delete Rating</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">Are you sure you want to delete this rating? This action cannot be undone.</p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button @click="confirmDelete" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 mr-2">
                            Delete
                        </button>
                        <button @click="closeDeleteModal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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

@push('scripts')
    @vite('resources/js/pages/dashboard.js')
@endpush
@endsection