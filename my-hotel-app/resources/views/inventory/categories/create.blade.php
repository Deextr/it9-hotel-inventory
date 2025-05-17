@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Add New Category') }}
                </h2>
                <a href="{{ route('inventory.categories.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center p-1 rounded-full hover:bg-gray-100" title="Back to List">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('inventory.categories.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input id="name" name="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('name') }}" required autofocus>
                                @error('name') 
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="flex items-center">
                                <input id="is_active" name="is_active" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="1" checked>
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 flex items-center px-4 py-2 rounded-md font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Create Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection