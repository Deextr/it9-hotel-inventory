@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Inventory Report
                </h2>
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
            
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Date Navigation -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Report Settings</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label for="period" class="block text-sm font-medium text-gray-700 mb-2">Report Period</label>
                            <div class="relative">
                                <select id="period" 
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                        onchange="updateReportPeriod(this.value)">
                                    <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date Selection</label>
                            <div class="relative">
                                <input type="date" name="date" id="date" value="{{ $date }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       onchange="updateReportDate(this.value)">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Navigation</label>
                            <div class="flex justify-between items-center bg-gray-50 rounded-md border border-gray-300 p-2">
                                <a href="{{ route('inventory.reports.index', ['period' => $period, 'date' => $previousPeriod]) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Previous
                                </a>
                                
                                <div class="text-sm font-medium text-gray-900 px-2">
                                    @if($period == 'daily')
                                        {{ $selectedDate->format($dateFormat) }}
                                    @elseif($period == 'weekly')
                                        {{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }}
                                    @elseif($period == 'monthly')
                                        {{ $selectedDate->format($dateFormat) }}
                                    @elseif($period == 'yearly')
                                        {{ $selectedDate->format($dateFormat) }}
                                    @endif
                                </div>
                                
                                <a href="{{ route('inventory.reports.index', ['period' => $period, 'date' => $nextPeriod]) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Next
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex justify-end">
                        <div class="flex space-x-2">
                            <a href="{{ route('inventory.reports.export', ['period' => $period, 'date' => $date]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                                Export to PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Inventory Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Stock Movements Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <div class="flex items-center">
                        <div class="mr-4 w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm uppercase font-semibold">STOCK MOVEMENTS</p>
                            <p class="text-2xl font-bold text-gray-700">{{ $summary['stockMovementsCount'] }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Items Assigned Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <div class="flex items-center">
                        <div class="mr-4 w-12 h-12 flex items-center justify-center bg-green-100 text-green-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm uppercase font-semibold">ITEMS ASSIGNED</p>
                            <p class="text-2xl font-bold text-gray-700">{{ $summary['itemsAssignedCount'] }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Items Pulled Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <div class="flex items-center">
                        <div class="mr-4 w-12 h-12 flex items-center justify-center bg-red-100 text-red-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm uppercase font-semibold">ITEMS PULLED</p>
                            <p class="text-2xl font-bold text-gray-700">{{ $summary['itemsPulledCount'] }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Transfers Card -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <div class="flex items-center">
                        <div class="mr-4 w-12 h-12 flex items-center justify-center bg-yellow-100 text-yellow-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm uppercase font-semibold">TRANSFERS</p>
                            <p class="text-2xl font-bold text-gray-700">{{ $summary['transfersCount'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Movement History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Stock Movement History</h3>
                    
                    @if($stockMovements->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($stockMovements as $movement)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $movement->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $movement->item->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($movement->type == 'in')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        In
                                                    </span>
                                                @elseif($movement->type == 'out')
                                                    @if(str_starts_with($movement->notes ?? '', 'Pullout:'))
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Pulled
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            Assigned
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Transfer
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $movement->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $movement->fromLocation ? $movement->fromLocation->name : 'Inventory' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $movement->toLocation ? $movement->toLocation->name : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $movement->notes ?? 'No notes' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $stockMovements->appends(['period' => $period, 'date' => $date])->links() }}
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-md p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No stock movements found</h3>
                            <p class="mt-1 text-sm text-gray-500">No stock movements were recorded during this period.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Data Analysis Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Top Items -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Top Items</h3>
                        
                        @if($topItems->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($topItems as $item)
                                    <li class="py-3 flex justify-between items-center">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $item->item->name }}</h4>
                                            <p class="text-xs text-gray-500">{{ $item->item->category->name ?? 'No Category' }}</p>
                                        </div>
                                        <div class="ml-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $item->total_quantity }} items
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 text-sm">No data available for this period.</p>
                        @endif
                    </div>
                </div>
                
                <!-- Top Locations -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Top Locations</h3>
                        
                        @if($topLocations->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($topLocations as $location)
                                    <li class="py-3 flex justify-between items-center">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $location->toLocation->name }}</h4>
                                            <p class="text-xs text-gray-500">
                                                Floor {{ $location->toLocation->floor_number }}, 
                                                {{ ucfirst($location->toLocation->area_type) }}
                                                @if($location->toLocation->room_number)
                                                    , Room {{ $location->toLocation->room_number }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="ml-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $location->total_movements }} movements
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 text-sm">No data available for this period.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Purchase Orders Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Purchase Orders</h3>
                    
                    @if($purchaseOrders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Items</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($purchaseOrders as $order)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('inventory.purchase_orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    #{{ $order->id }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $order->supplier->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($order->status == 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                @elseif($order->status == 'delivered')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Delivered
                                                    </span>
                                                @elseif($order->status == 'canceled')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Canceled
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $order->items->sum('quantity') }} items
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-md p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No purchase orders found</h3>
                            <p class="mt-1 text-sm text-gray-500">No purchase orders were created during this period.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function updateReportDate(date) {
            let period = document.getElementById('period').value;
            window.location.href = "{{ route('inventory.reports.index') }}?period=" + period + "&date=" + date;
        }
        
        function updateReportPeriod(period) {
            let date = document.getElementById('date').value;
            window.location.href = "{{ route('inventory.reports.index') }}?period=" + period + "&date=" + date;
        }
    </script>
@endsection 