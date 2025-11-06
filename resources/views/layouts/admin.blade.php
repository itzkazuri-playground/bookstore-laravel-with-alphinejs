<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title')@yield('title') | @endif{{ config('app.name', 'Bookstore') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/admin/admin.css'])
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    @auth
        @if(auth()->user()->isAdmin())
    <div class="min-h-screen flex">
        <!-- Admin Sidebar Navigation -->
        <nav class="bg-gray-800 text-white w-64 min-h-screen">
            <div class="p-5">
                <h1 class="text-xl font-bold">Admin Panel</h1>
            </div>
            <ul class="mt-6">
                <li class="mb-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700' }} block px-4 py-2 pl-3">
                        Dashboard
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('admin.books') }}" 
                       class="{{ request()->routeIs('admin.books') ? 'bg-gray-900 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700' }} block px-4 py-2 pl-3">
                        Books
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('admin.authors') }}" 
                       class="{{ request()->routeIs('admin.authors') ? 'bg-gray-900 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700' }} block px-4 py-2 pl-3">
                        Authors
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('admin.ratings') }}" 
                       class="{{ request()->routeIs('admin.ratings') ? 'bg-gray-900 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700' }} block px-4 py-2 pl-3">
                        Ratings
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content Area -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <nav class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-end h-16">
                        <div class="flex items-center">
                            <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Admin Content -->
            <main class="py-6">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
                
                @if(session('success'))
                <script>
                    Swal.fire({
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                </script>
                @endif
                
                @if(session('error'))
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: '{{ session('error') }}',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>
                @endif
            </main>
        </div>
    </div>
    
    <!-- Include admin JS -->
    @vite('resources/js/admin/admin.js')
    
    @else
        <div class="flex items-center justify-center min-h-screen bg-gray-100">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-red-500 mb-4">Access Denied</h1>
                <p class="text-gray-700 mb-4">You do not have permission to access this page.</p>
                <a href="/" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Go to Home</a>
            </div>
        </div>
    @endif
    @else
        <div class="flex items-center justify-center min-h-screen bg-gray-100">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-red-500 mb-4">Access Required</h1>
                <p class="text-gray-700 mb-4">Please log in to access this page.</p>
                <a href="/login" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Login</a>
            </div>
        </div>
    @endif
</body>
</html>