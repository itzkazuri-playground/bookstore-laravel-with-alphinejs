@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
            @if($adminUser)
            <div class="text-right">
                <p class="text-sm text-gray-600">Welcome back,</p>
                <p class="text-lg font-semibold text-gray-800">{{ $adminUser->name }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Books</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $booksCount ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Authors</h3>
            <p class="text-3xl font-bold text-green-600">{{ $authorsCount ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Categories</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $categoriesCount ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Ratings</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $ratingsCount ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.books') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded text-center">
                Manage Books
            </a>
            <a href="{{ route('admin.authors') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded text-center">
                Manage Authors
            </a>
            <a href="{{ route('admin.ratings') }}" class="bg-yellow-500 hover:bg-green-600 text-white px-4 py-3 rounded text-center">
                Manage Ratings
            </a>
        </div>
    </div>
</div>
@endsection