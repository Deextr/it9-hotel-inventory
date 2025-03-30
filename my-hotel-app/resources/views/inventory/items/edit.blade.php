@extends('layouts.app')

@section('header')
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Edit Item') }}
                </h2>
                <a href="{{ route('inventory.items.index') }}" class="btn-secondary">Back to List</a>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card overflow-hidden">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('inventory.items.update', $item) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input id="name" name="name" type="text" class="mt-1 input-field block w-full" value="{{ old('name', $item->name) }}" required autofocus>
                                @error('name') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                <select id="category_id" name="category_id" required class="mt-1 input-field block w-full">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea id="description" name="description" class="mt-1 input-field block w-full" rows="3">{{ old('description', $item->description) }}</textarea>
                                @error('description') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="btn-primary">Update Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection