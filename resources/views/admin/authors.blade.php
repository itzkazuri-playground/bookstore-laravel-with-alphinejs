@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Manage Authors</h1>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.authors') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ request('search') }}" placeholder="Name, Bio, Country...">
                </div>
                
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                    <select name="country" id="country" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Countries</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                                {{ $country }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mr-2">
                        Filter
                    </button>
                    <a href="{{ route('admin.authors') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Authors List</h2>
            <a href="{{ route('admin.authors.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Add New Author
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Books Count</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($authors as $author)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $author->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $author->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $author->country ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $author->books->count() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.authors.edit', $author) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" class="inline" onsubmit="return confirmDelete(event, 'author')">
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
            {{ $authors->links() }}
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