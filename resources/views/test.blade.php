@extends('layouts.app')

@section('title', 'Test Page')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-3xl font-bold mb-6">This is a Test Page</h1>
                <p class="mb-6">This page demonstrates the dynamic title functionality. The page title in the browser tab should now show "Test Page | Bookstore".</p>
                <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Back to Home</a>
            </div>
        </div>
    </div>
</div>
@endsection