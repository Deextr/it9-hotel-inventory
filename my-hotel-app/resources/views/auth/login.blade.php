@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-[#2c3e50] text-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold">Welcome Back</h1>
            <p class="mt-2 text-gray-200">Sign in to manage your hotel inventory</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="bg-white shadow-2xl rounded-lg overflow-hidden">
            <div class="bg-[#2563eb] px-6 py-4">
                <h2 class="text-xl font-semibold text-white">Login to Your Account</h2>
            </div>
            
            <form method="POST" action="{{ route('login') }}" class="px-6 py-4">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-medium block mb-1" />
                    <x-text-input id="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-800" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="your.email@example.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium block mb-1" />
                    <x-text-input id="password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-800"
                                type="password"
                                name="password"
                                required autocomplete="current-password" 
                                placeholder="Enter your password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="mb-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded bg-white border-gray-300 text-blue-600 focus:ring-blue-500" name="remember">
                        <span class="ms-2 text-sm text-gray-700">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full px-5 py-3 bg-[#2563eb] border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-[#1d4ed8] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Sign In') }}
                    </button>
                </div>
                
                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-gray-600 hover:text-blue-700 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                    
                    <a class="text-sm text-gray-600 hover:text-blue-700 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" href="{{ route('register') }}">
                        {{ __('Need an account? Sign up') }}
                    </a>
                </div>
            </form>
            
            <div class="px-6 py-3 bg-gray-50 text-center">
                <p class="text-xs text-gray-600">Secure login protected by SSL encryption</p>
            </div>
        </div>
    </div>
</div>
@endsection
