@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-[#2c3e50] text-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold">Join Our Platform</h1>
            <p class="mt-2 text-gray-200">Create your account to manage hotel inventory efficiently</p>
        </div>

        <div class="bg-white shadow-2xl rounded-lg overflow-hidden">
            <div class="bg-[#2563eb] px-6 py-4">
                <h2 class="text-xl font-semibold text-white">Register Your Account</h2>
            </div>
            
            <form method="POST" action="{{ route('register') }}" class="px-6 py-4">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Full Name')" class="text-gray-700 font-medium block mb-1" />
                    <x-text-input id="name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-800" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your full name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-medium block mb-1" />
                    <x-text-input id="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-800" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="your.email@example.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium block mb-1" />
                    <x-text-input id="password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-800"
                                type="password"
                                name="password"
                                required autocomplete="new-password" 
                                placeholder="Choose a secure password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 font-medium block mb-1" />
                    <x-text-input id="password_confirmation" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-800"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" 
                                placeholder="Confirm your password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full px-5 py-3 bg-[#2563eb] border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-[#1d4ed8] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Create Account') }}
                    </button>
                </div>
                
                <div class="flex items-center justify-center mt-4">
                    <a class="text-sm text-gray-600 hover:text-blue-700 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" href="{{ route('login') }}">
                        {{ __('Already have an account? Sign in') }}
                    </a>
                </div>
            </form>
            
            <div class="px-6 py-3 bg-gray-50 text-center">
                <p class="text-xs text-gray-600">By registering, you agree to our <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
