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

                            <div class="mb-4">
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                            @if(!$category->is_active)
                                                (Inactive)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea id="description" name="description" class="mt-1 input-field block w-full" rows="3">{{ old('description', $item->description) }}</textarea>
                                @error('description') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <div class="flex items-center">
                                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ $item->is_active ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Inactive items won't be displayed in selection lists and can't be assigned to locations.</p>
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