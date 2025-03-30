@extends('layouts.app')

@section('header')
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Edit Category') }}
                </h2>
                <a href="{{ route('inventory.categories.index') }}" class="btn-secondary">
                    Back to List
                </a>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card overflow-hidden">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('inventory.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input id="name" name="name" type="text" class="mt-1 input-field block w-full" value="{{ old('name', $category->name) }}" required autofocus>
                                @error('name') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="btn-primary">
                                {{ __('Update Category') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection