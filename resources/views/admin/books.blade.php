@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Manage Books</h1>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.books') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ request('search') }}" placeholder="Title, Author, ISBN...">
                </div>
                
                <div>
                    <label for="author_id" class="block text-sm font-medium text-gray-700">Author</label>
                    <select name="author_id" id="author_id" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Authors</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ request('author_id') == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="availability_status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="availability_status" id="availability_status" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('availability_status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mr-2">
                        Filter
                    </button>
                    <a href="{{ route('admin.books') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        Clear
                    </a>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label for="from_year" class="block text-sm font-medium text-gray-700">From Year</label>
                    <input type="number" name="from_year" id="from_year" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ request('from_year') }}" min="1000" max="{{ date('Y') + 5 }}" placeholder="e.g. 2000">
                </div>
                
                <div>
                    <label for="to_year" class="block text-sm font-medium text-gray-700">To Year</label>
                    <input type="number" name="to_year" id="to_year" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ request('to_year') }}" min="1000" max="{{ date('Y') + 5 }}" placeholder="e.g. 2023">
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Books List</h2>
            <a href="{{ route('admin.books.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Add New Book
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($books as $book)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $book->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $book->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $book->author->name ?? 'Unknown' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $book->availability_status === 'available' ? 'bg-green-100 text-green-800' : 
                                   ($book->availability_status === 'rented' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800') }}">
                                {{ ucfirst($book->availability_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.books.edit', $book) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline" onsubmit="return confirmDelete(event, 'book')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $books->links() }}
        </div>
    </div>
</div>

<script>
function confirmDelete(event, type) {
    event.preventDefault();
    const form = event.target;
    
    Swal.fire({
        title: 'Are you sure?',
        text: `You won't be able to revert this!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        width: '400px',
        padding: '1rem'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>
@endsection