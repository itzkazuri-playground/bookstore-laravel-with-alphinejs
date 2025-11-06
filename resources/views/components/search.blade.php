<div class="relative" x-data="{ searchQuery: '', searchType: 'books' }">
    <div class="flex">
        <select 
            class="bg-gray-50 border border-r-0 border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
            x-model="searchType"
        >
            <option value="books">Books</option>
            <option value="authors">Authors</option>
        </select>
        <input 
            type="text" 
            placeholder="Search..." 
            class="block w-full md:w-64 px-3 py-2 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            x-model="searchQuery"
            @keyup.enter="() => {
                if (searchType === 'books') {
                    window.location.href = '{{ route('books.index') }}?search=' + encodeURIComponent(searchQuery);
                } else {
                    window.location.href = '{{ route('authors.index') }}?search=' + encodeURIComponent(searchQuery);
                }
            }"
        >
        <button 
            class="ml-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200"
            @click="() => {
                if (searchType === 'books') {
                    window.location.href = '{{ route('books.index') }}?search=' + encodeURIComponent(searchQuery);
                } else {
                    window.location.href = '{{ route('authors.index') }}?search=' + encodeURIComponent(searchQuery);
                }
            }"
        >
            Search
        </button>
    </div>
</div>