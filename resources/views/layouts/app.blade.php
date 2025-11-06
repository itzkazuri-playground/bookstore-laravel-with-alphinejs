<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@hasSection('title')@yield('title') | @endif{{ config('app.name', 'Bookstore') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/pages/dashboard.css'])
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Header/Navigation -->
            <nav class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center">
                                <span class="font-bold text-xl text-gray-800">Bookstore</span>
                            </div>
                            <div class="hidden md:ml-6 md:flex md:space-x-8">
                                <a href="{{ route('home') }}" class="border-b-2 border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">
                                    Home
                                </a>
                                <a href="{{ route('books.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 text-sm font-medium">
                                    Books
                                </a>
                                <a href="{{ route('authors.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 text-sm font-medium">
                                    Top Authors
                                </a>
                                <a href="{{ route('ratings.create') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 text-sm font-medium">
                                    Rate Book
                                </a>
                            </div>
                        </div>
                        
                        <!-- Right side navigation -->
                        <div class="flex items-center">
                            @auth
                                <div class="relative">
                                    <button id="user-menu-button" class="flex items-center space-x-2">
                                        <span>{{ Auth::user()->name }}</span>
                                        <svg class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM2 10a8 8 0 1116 0 8 8 0 01-16 0z" />
                                        </svg>
                                    </button>
                                    <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden">
                                        @if(Auth::user()->isAdmin())
                                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                                        @else
                                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                        @endif
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <!-- User is not logged in - show single login -->
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 mx-4">Login</a>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>
        @stack('scripts')
        @vite(['resources/js/pages/dashboard.js'])
    </body>
</html>