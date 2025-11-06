@extends('layouts.app')

@section('title', 'Edit Profile')

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">Edit Profile</h1>

                @if(session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input id="name" name="name" type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                        @error('name')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="email" name="email" type="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                        @error('email')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-2 text-sm text-gray-600 bg-yellow-50 p-3 rounded">
                                Your email address is unverified.
                                <form method="post" action="{{ route('verification.send') }}" class="inline mt-1">
                                    @csrf
                                    <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Click here to re-send the verification email.
                                    </button>
                                </form>
                            </div>

                            @if (session('status') === 'verification-link-sent')
                                <div class="mt-2 text-sm font-medium text-green-600">
                                    A new verification link has been sent to your email address.
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="pt-6 border-t border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Update Password</h2>
                        <p class="text-sm text-gray-600 mb-4">Leave empty if you don't want to change your password.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-1">
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                <input id="current_password" name="current_password" type="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" autocomplete="current-password" placeholder="Current password" />
                                @error('current_password')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-1">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <input id="password" name="password" type="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" autocomplete="new-password" placeholder="New password" />
                                @error('password')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-1">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" autocomplete="new-password" placeholder="Confirm new password" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Changes
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p class="text-sm text-green-600 font-medium">Profile updated successfully!</p>
                        @endif
                    </div>
                </form>

                <div class="mt-12 pt-8 border-t border-gray-200">
                    <h2 class="text-xl font-bold mb-4">Delete Account</h2>
                    <p class="text-sm text-gray-600 mb-4">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>

                    <button 
                        data-delete-button
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md"
                    >
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Meta tag for delete route -->
<meta name="delete-action" content="{{ route('profile.destroy') }}">

<!-- Include the profile-specific JavaScript -->
@vite('resources/js/pages/profile.js')
@endsection