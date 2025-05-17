@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Suppliers
                </h2>
                <a href="{{ route('inventory.suppliers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-5 rounded-lg shadow-md transition duration-150 ease-in-out flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Supplier
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Alerts -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-md" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <form action="{{ route('inventory.suppliers.index') }}" method="GET">
                    <!-- Filters & Search -->
                    <div class="flex flex-col space-y-4 md:space-y-0 md:flex-row md:justify-between md:items-end">
                        <div class="flex flex-wrap items-center gap-3 mb-4 md:mb-0">
                            <span class="text-gray-700 font-medium">Filter:</span>
                            <a href="{{ route('inventory.suppliers.index', array_merge(request()->except('status'), ['page' => 1])) }}" class="px-4 py-2 rounded-full text-sm {{ !request()->has('status') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition-colors duration-200">
                                All
                            </a>
                            <a href="{{ route('inventory.suppliers.index', array_merge(request()->except('status'), ['status' => 'active', 'page' => 1])) }}" class="px-4 py-2 rounded-full text-sm {{ request('status') === 'active' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition-colors duration-200">
                                Active
                            </a>
                            <a href="{{ route('inventory.suppliers.index', array_merge(request()->except('status'), ['status' => 'inactive', 'page' => 1])) }}" class="px-4 py-2 rounded-full text-sm {{ request('status') === 'inactive' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition-colors duration-200">
                                Inactive
                            </a>
                        </div>
                        
                        <div class="w-full md:w-auto">
                            <div class="flex">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search suppliers..." class="px-4 py-2 border rounded-l focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r hover:bg-blue-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                @if($suppliers->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <p class="text-gray-500 text-lg">No suppliers found</p>
                        <a href="{{ route('inventory.suppliers.create') }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                            Create your first supplier
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Person</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($suppliers as $supplier)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $supplier->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-700">{{ $supplier->contact_person }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-700">
                                                <a href="mailto:{{ $supplier->email }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ $supplier->email }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-700">
                                                <a href="tel:{{ $supplier->phone }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ $supplier->phone }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-700">{{ $supplier->address }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($supplier->is_active)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('inventory.suppliers.edit', $supplier) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center p-1 rounded-full hover:bg-indigo-100" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </a>
                                                
                                                <form method="POST" action="{{ route('inventory.suppliers.toggle-status', $supplier) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="{{ $supplier->is_active ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }} flex items-center p-1 rounded-full {{ $supplier->is_active ? 'hover:bg-yellow-100' : 'hover:bg-green-100' }}" title="{{ $supplier->is_active ? 'Deactivate' : 'Activate' }}">
                                                        @if ($supplier->is_active)
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                        @endif
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <nav class="relative z-0 inline-flex shadow-sm -space-x-px rounded-md" aria-label="Pagination">
                            <!-- Previous Page Link -->
                            @if ($suppliers->onFirstPage())
                                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $suppliers->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            @endif
                            
                            <!-- Pagination Elements -->
                            @for ($i = 1; $i <= $suppliers->lastPage(); $i++)
                                @if ($i == $suppliers->currentPage())
                                    <!-- Current Page -->
                                    <span aria-current="page" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">
                                        {{ $i }}
                                    </span>
                                @else
                                    <a href="{{ $suppliers->url($i) }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor
                            
                            <!-- Next Page Link -->
                            @if ($suppliers->hasMorePages())
                                <a href="{{ $suppliers->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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
                @endif
            </div>
        </div>
    </div>
</div>
@endsection