@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Inventory Stock Management
                </h2>
                <div class="flex space-x-2">
                    <a href="{{ route('inventory.purchase_orders.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Purchase Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Inventory Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Total Items Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <div class="flex items-center">
                        <div class="mr-4 w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm uppercase font-semibold">TOTAL ITEMS</p>
                            <p class="text-2xl font-bold text-gray-700">{{ $items->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- In Stock Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <div class="flex items-center">
                        <div class="mr-4 w-12 h-12 flex items-center justify-center bg-green-100 text-green-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm uppercase font-semibold">IN STOCK</p>
                            <p class="text-2xl font-bold text-gray-700">{{ $items->filter(function($item) { return $item->getCurrentStock() > 0; })->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Low Stock Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <div class="flex items-center">
                        <div class="mr-4 w-12 h-12 flex items-center justify-center bg-yellow-100 text-yellow-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm uppercase font-semibold">LOW STOCK</p>
                            <p class="text-2xl font-bold text-gray-700">
                                {{ $items->filter(function($item) { 
                                    return $item->getCurrentStock() > 0 && $item->inventory && $item->getCurrentStock() <= $item->inventory->reorder_level; 
                                })->count() }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Out of Stock Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <div class="flex items-center">
                        <div class="mr-4 w-12 h-12 flex items-center justify-center bg-red-100 text-red-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm uppercase font-semibold">OUT OF STOCK</p>
                            <p class="text-2xl font-bold text-gray-700">{{ $items->filter(function($item) { return $item->getCurrentStock() <= 0; })->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Filter by Category</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('inventory.view') }}" 
                           class="px-4 py-2 text-sm font-medium rounded-md transition-colors border
                           {{ !isset($category) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200' }}">
                            All Categories
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('inventory.view.category', $cat) }}" 
                               class="px-4 py-2 text-sm font-medium rounded-md transition-colors border
                               {{ isset($category) && $category->id === $cat->id ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200' }}">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Category Filter for Mobile - Dropdown -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 md:hidden">
                <div class="p-4 bg-white border-b border-gray-200">
                    <label for="category-select" class="block text-sm font-medium text-gray-700 mb-2">Select Category</label>
                    <select id="category-select" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" onchange="window.location.href=this.value">
                        <option value="{{ route('inventory.view') }}" {{ !isset($category) ? 'selected' : '' }}>All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ route('inventory.view.category', $cat) }}" {{ isset($category) && $category->id === $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Inventory Items Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            @if(isset($category))
                                {{ $category->name }} Items
                            @else
                                All Inventory Items
                            @endif
                        </h3>
                        
                        <div>
                            <form action="{{ route('inventory.view') }}" method="GET" class="flex">
                                <input type="text" name="search" placeholder="Search items..." 
                                       class="block w-full rounded-l-md border-gray-300 shadow-sm 
                                              focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent 
                                               rounded-r-md font-semibold text-xs text-white uppercase tracking-widest 
                                               hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 
                                               focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    SEARCH
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($items as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $item->category->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 truncate max-w-xs">{{ $item->description ?: 'No description' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $stockLevel = $item->getCurrentStock();
                                                $reorderLevel = $item->inventory ? $item->inventory->reorder_level : 0;
                                            @endphp
                                            
                                            @if ($stockLevel > 0)
                                                @if ($stockLevel <= $reorderLevel)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Low: {{ $stockLevel }}
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ $stockLevel }} in stock
                                                    </span>
                                                @endif
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Out of stock
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->inventory && $item->inventory->last_stocked_at ? $item->inventory->last_stocked_at->format('Y-m-d H:i') : 'Never' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->inventory && $item->inventory->supplier_name ? $item->inventory->supplier_name : 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No inventory items found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 