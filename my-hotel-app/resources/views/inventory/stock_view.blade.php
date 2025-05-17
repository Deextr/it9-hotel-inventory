@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Inventory Stock Management
                </h2>
                <div class="flex space-x-2">
                    <a href="{{ route('inventory.purchase_orders.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center p-1 rounded-full hover:bg-indigo-100" title="Purchase Orders">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                        </svg>
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
                        <option value="{{ route('inventory.view', ['status' => $status ?? '']) }}" {{ !isset($category) ? 'selected' : '' }}>All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ route('inventory.view.category', ['category' => $cat, 'status' => $status ?? '']) }}" {{ isset($category) && $category->id === $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Status Filter for Mobile - Dropdown -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 md:hidden">
                <div class="p-4 bg-white border-b border-gray-200">
                    <label for="status-select" class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                    <select id="status-select" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" onchange="window.location.href=this.value">
                        <option value="{{ isset($category) ? route('inventory.view.category', $category) : route('inventory.view') }}" {{ !isset($status) ? 'selected' : '' }}>All Items</option>
                        <option value="{{ isset($category) ? route('inventory.view.category', ['category' => $category, 'status' => 'active']) : route('inventory.view', ['status' => 'active']) }}" {{ isset($status) && $status === 'active' ? 'selected' : '' }}>Active Items</option>
                        <option value="{{ isset($category) ? route('inventory.view.category', ['category' => $category, 'status' => 'inactive']) : route('inventory.view', ['status' => 'inactive']) }}" {{ isset($status) && $status === 'inactive' ? 'selected' : '' }}>Inactive Items</option>
                    </select>
                </div>
            </div>

            <!-- Stock Level Filter for Mobile - Dropdown -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 md:hidden">
                <div class="p-4 bg-white border-b border-gray-200">
                    <label for="stock-select" class="block text-sm font-medium text-gray-700 mb-2">Filter by Stock Level</label>
                    <select id="stock-select" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" onchange="window.location.href=this.value">
                        <option value="{{ isset($category) ? route('inventory.view.category', ['category' => $category, 'status' => $status ?? '']) : route('inventory.view', ['status' => $status ?? '']) }}" {{ !isset($stockFilter) ? 'selected' : '' }}>All Stock Levels</option>
                        <option value="{{ isset($category) ? route('inventory.view.category', ['category' => $category, 'status' => $status ?? '', 'stock' => 'in-stock']) : route('inventory.view', ['status' => $status ?? '', 'stock' => 'in-stock']) }}" {{ isset($stockFilter) && $stockFilter === 'in-stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="{{ isset($category) ? route('inventory.view.category', ['category' => $category, 'status' => $status ?? '', 'stock' => 'low-stock']) : route('inventory.view', ['status' => $status ?? '', 'stock' => 'low-stock']) }}" {{ isset($stockFilter) && $stockFilter === 'low-stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="{{ isset($category) ? route('inventory.view.category', ['category' => $category, 'status' => $status ?? '', 'stock' => 'out-of-stock']) : route('inventory.view', ['status' => $status ?? '', 'stock' => 'out-of-stock']) }}" {{ isset($stockFilter) && $stockFilter === 'out-of-stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
            </div>

            <!-- Inventory Items Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                @if(isset($category))
                                    {{ $category->name }} Items
                                @else
                                    All Inventory Items
                                @endif
                                @if(isset($status))
                                    <span class="text-sm text-gray-500 ml-2">({{ ucfirst($status) }} only)</span>
                                @endif
                            </h3>
                            
                            <!-- Status Filter -->
                            <div class="flex gap-2">
                                <a href="{{ isset($category) ? route('inventory.view.category', ['category' => $category->id]) : route('inventory.view') }}" 
                                   class="px-3 py-1 text-xs font-medium rounded-md transition-colors border
                                   {{ !isset($status) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200' }}">
                                    All
                                </a>
                                <a href="{{ isset($category) ? route('inventory.view.category', ['category' => $category->id, 'status' => 'active']) : route('inventory.view', ['status' => 'active']) }}" 
                                   class="px-3 py-1 text-xs font-medium rounded-md transition-colors border
                                   {{ isset($status) && $status === 'active' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200' }}">
                                    Active
                                </a>
                                <a href="{{ isset($category) ? route('inventory.view.category', ['category' => $category->id, 'status' => 'inactive']) : route('inventory.view', ['status' => 'inactive']) }}" 
                                   class="px-3 py-1 text-xs font-medium rounded-md transition-colors border
                                   {{ isset($status) && $status === 'inactive' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200' }}">
                                    Inactive
                                </a>
                            </div>
                            
                            <!-- Stock Level Filter -->
                            <div class="flex gap-2 mt-2 md:mt-0 md:ml-4">
                                <span class="text-xs font-medium text-gray-500 self-center mr-1">Stock Level:</span>
                                <a href="{{ isset($category) ? route('inventory.view.category', ['category' => $category->id, 'status' => $status ?? null]) : route('inventory.view', ['status' => $status ?? null]) }}" 
                                   class="px-3 py-1 text-xs font-medium rounded-md transition-colors border
                                   {{ !isset($stockFilter) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200' }}">
                                    All
                                </a>
                                <a href="{{ isset($category) ? route('inventory.view.category', ['category' => $category->id, 'status' => $status ?? null, 'stock' => 'in-stock']) : route('inventory.view', ['status' => $status ?? null, 'stock' => 'in-stock']) }}" 
                                   class="px-3 py-1 text-xs font-medium rounded-md transition-colors border
                                   {{ isset($stockFilter) && $stockFilter === 'in-stock' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200' }}">
                                    In Stock
                                </a>
                                <a href="{{ isset($category) ? route('inventory.view.category', ['category' => $category->id, 'status' => $status ?? null, 'stock' => 'low-stock']) : route('inventory.view', ['status' => $status ?? null, 'stock' => 'low-stock']) }}" 
                                   class="px-3 py-1 text-xs font-medium rounded-md transition-colors border
                                   {{ isset($stockFilter) && $stockFilter === 'low-stock' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200' }}">
                                    Low Stock
                                </a>
                                <a href="{{ isset($category) ? route('inventory.view.category', ['category' => $category->id, 'status' => $status ?? null, 'stock' => 'out-of-stock']) : route('inventory.view', ['status' => $status ?? null, 'stock' => 'out-of-stock']) }}" 
                                   class="px-3 py-1 text-xs font-medium rounded-md transition-colors border
                                   {{ isset($stockFilter) && $stockFilter === 'out-of-stock' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200' }}">
                                    Out of Stock
                                </a>
                            </div>
                        </div>
                        
                        <div>
                            <form action="{{ isset($category) ? route('inventory.view.category', $category) : route('inventory.view') }}" method="GET" class="flex">
                                @if(isset($status))
                                    <input type="hidden" name="status" value="{{ $status }}">
                                @endif
                                @if(isset($stockFilter))
                                    <input type="hidden" name="stock" value="{{ $stockFilter }}">
                                @endif
                                <input type="text" name="search" placeholder="Search items..." 
                                       class="block w-full rounded-l-md border-gray-300 shadow-sm 
                                              focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       value="{{ request()->search }}">
                                <button type="submit" 
                                        class="text-gray-600 hover:text-gray-900 flex items-center p-1 rounded-full hover:bg-gray-100" title="Search">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Level</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($item->is_active)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->inventory && $item->inventory->last_stocked_at ? $item->inventory->last_stocked_at->format('Y-m-d H:i') : 'Never' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $stockLevel = $item->getCurrentStock();
                                                $reorderLevel = $item->inventory ? $item->inventory->reorder_level : 0;
                                                
                                                if ($reorderLevel > 0) {
                                                    $ratio = $stockLevel > 0 ? $stockLevel / $reorderLevel : 0;
                                                } else {
                                                    $ratio = $stockLevel > 0 ? 1 : 0;
                                                }
                                            @endphp
                                            
                                            @if ($stockLevel <= 0)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Need to Order
                                                </span>
                                            @elseif ($ratio <= 0.25)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Critical
                                                </span>
                                            @elseif ($ratio <= 0.5)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Warning
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Good
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No inventory items found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-6 flex justify-end">
                        <nav class="relative z-0 inline-flex shadow-sm -space-x-px rounded-md" aria-label="Pagination">
                            <!-- Previous Page Link -->
                            @if ($items->onFirstPage())
                                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $items->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            @endif
                            
                            <!-- Pagination Elements -->
                            @for ($i = 1; $i <= $items->lastPage(); $i++)
                                @if ($i == $items->currentPage())
                                    <!-- Current Page -->
                                    <span aria-current="page" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">
                                        {{ $i }}
                                    </span>
                                @else
                                    <a href="{{ $items->url($i) }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor
                            
                            <!-- Next Page Link -->
                            @if ($items->hasMorePages())
                                <a href="{{ $items->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 