@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div x-data="errorPage()" class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="mx-auto h-48 w-48">
                <img src="{{ asset('images/404.png') }}" alt="404 Error" class="h-full w-full object-contain">
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Oops! Page Not Found</h2>
            <p class="mt-2 text-sm text-gray-600">Sorry, the page you're looking for doesn't exist or has been moved.</p>
        </div>
        
        <div class="mt-8 space-y-4">
            <div class="bg-white shadow rounded-md p-6">
                <h3 class="text-lg font-medium text-gray-900">What happened?</h3>
                <p class="mt-2 text-sm text-gray-600">
                    The page you requested could not be found. This could be due to a variety of reasons:
                </p>
                <ul class="mt-3 space-y-1 text-left text-sm text-gray-600 list-disc list-inside">
                    <li>The URL may be misspelled</li>
                    <li>The page may have been moved to a different location</li>
                    <li>The page may have been removed</li>
                    <li>You may have followed an outdated link</li>
                </ul>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <button 
                    @click="goBack()" 
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    x-show="hasHistory"
                    x-text="backButtonLabel"
                >
                    Go Back
                </button>
                
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function errorPage() {
    return {
        hasHistory: window.history.length > 1,
        backButtonLabel: window.history.length > 1 ? 'Go Back to Previous Page' : 'No Previous Page',
        
        goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = "{{ route('home') }}";
            }
        }
    }
}
</script>
@endsection