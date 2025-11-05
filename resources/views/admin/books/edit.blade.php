@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Edit Book</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('admin.books.update', $book) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                    <input type="text" name="title" id="title" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('title', $book->title) }}" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="author_id" class="block text-sm font-medium text-gray-700">Author *</label>
                    <select name="author_id" id="author_id" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('author_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                    <input type="text" name="isbn" id="isbn" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('isbn', $book->isbn) }}">
                    @error('isbn')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="publisher" class="block text-sm font-medium text-gray-700">Publisher</label>
                    <input type="text" name="publisher" id="publisher" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('publisher', $book->publisher) }}">
                    @error('publisher')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="publication_year" class="block text-sm font-medium text-gray-700">Publication Year</label>
                    <input type="number" name="publication_year" id="publication_year" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ old('publication_year', $book->publication_year) }}" min="1000" max="{{ date('Y') + 5 }}">
                    @error('publication_year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="availability_status" class="block text-sm font-medium text-gray-700">Availability Status *</label>
                    <select name="availability_status" id="availability_status" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="available" {{ old('availability_status', $book->availability_status) == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="rented" {{ old('availability_status', $book->availability_status) == 'rented' ? 'selected' : '' }}>Rented</option>
                        <option value="reserved" {{ old('availability_status', $book->availability_status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                    </select>
                    @error('availability_status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Update Book
                </button>
                <a href="{{ route('admin.books') }}" class="ml-3 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection