@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Manage Ratings</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Ratings List</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($ratings as $rating)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $rating->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $rating->book->title ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-yellow-500">★</span>
                                <span class="ml-1">{{ $rating->rating }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $rating->voter_identifier ?? 'Unknown' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $rating->rated_at ? $rating->rated_at->format('Y-m-d') : 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <form action="{{ route('admin.ratings.destroy', $rating) }}" method="POST" class="inline" onsubmit="return confirmDelete(event, 'rating')">
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
            {{ $ratings->links() }}
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