@extends('layouts.app')

@section('header')
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </header>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="card mb-6 overflow-hidden">
                <div class="p-6 flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full p-3 mr-4">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Welcome back, {{ Auth::user()->name }}!</h3>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">Here's an overview of your hotel inventory system.</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Items Card -->
                <div class="card overflow-hidden">
                    <div class="p-6 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-600 text-white mr-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium uppercase text-gray-500 dark:text-gray-400">Total Items</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\Item::count() }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('inventory.items.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View all items →</a>
                        </div>
                    </div>
                </div>

                <!-- Total Locations Card -->
                <div class="card overflow-hidden">
                    <div class="p-6 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-600 text-white mr-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium uppercase text-gray-500 dark:text-gray-400">Locations</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\Location::count() }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('locations.index') }}" class="text-sm text-green-600 dark:text-green-400 hover:underline">View all locations →</a>
                        </div>
                    </div>
                </div>

                <!-- Pending Purchase Orders Card -->
                <div class="card overflow-hidden">
                    <div class="p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-600 text-white mr-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium uppercase text-gray-500 dark:text-gray-400">Pending Orders</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\PurchaseOrder::where('status', 'pending')->count() }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('inventory.purchase_orders.index') }}" class="text-sm text-yellow-600 dark:text-yellow-400 hover:underline">View purchase orders →</a>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Items Card -->
                <div class="card overflow-hidden">
                    <div class="p-6 bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-600 text-white mr-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium uppercase text-gray-500 dark:text-gray-400">Low Stock Items</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\Item::whereHas('inventory', function($query) {
                                    $query->whereRaw('current_stock < reorder_level');
                                })->where('is_active', true)->count() }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('inventory.view') }}?filter=low_stock" class="text-sm text-red-600 dark:text-red-400 hover:underline">View low stock items →</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Extended Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Out of Stock Items Card -->
                <div class="card overflow-hidden">
                    <div class="p-6 bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-orange-600 text-white mr-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium uppercase text-gray-500 dark:text-gray-400">Out of Stock Items</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\Item::whereDoesntHave('inventory')->orWhereHas('inventory', function($query) {
                                    $query->where('current_stock', 0);
                                })->where('is_active', true)->count() }}</p>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Items requiring immediate reorder</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('inventory.view') }}?filter=out_of_stock" class="text-sm text-orange-600 dark:text-orange-400 hover:underline">View out of stock items →</a>
                        </div>
                    </div>
                </div>

                <!-- Inventory Value Card -->
                <div class="card overflow-hidden">
                    <div class="p-6 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-600 text-white mr-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium uppercase text-gray-500 dark:text-gray-400">Monthly Activity</p>
                                <div class="flex items-baseline">
                                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\StockMovement::whereMonth('created_at', now()->month)->count() }}</p>
                                    <p class="ml-2 text-sm text-gray-500 dark:text-gray-400">movements this month</p>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="text-green-500 font-medium">{{ \App\Models\StockMovement::where('type', 'in')->whereMonth('created_at', now()->month)->count() }}</span> in / 
                                    <span class="text-red-500 font-medium">{{ \App\Models\StockMovement::where('type', 'out')->whereMonth('created_at', now()->month)->count() }}</span> out
                                </p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('inventory.reports.index') }}" class="text-sm text-purple-600 dark:text-purple-400 hover:underline">View detailed reports →</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Inventory Distribution Chart -->
                <div class="card overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Inventory By Category</h3>
                        <div class="relative h-80">
                            <canvas id="categoryDistributionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Stock Movement Trends Chart -->
                <div class="card overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Recent Stock Movements</h3>
                        <div class="relative h-80">
                            <canvas id="stockMovementChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Recent Activity</h3>
                        <a href="{{ route('audit-logs.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">View all activity →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Details</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                                @forelse(\App\Models\AuditLog::with('user')->latest()->take(5)->get() as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 flex items-center justify-center rounded-full 
                                                @if(Str::contains($log->action, ['create', 'add', 'deliver'])) 
                                                    bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-200
                                                @elseif(Str::contains($log->action, ['delete', 'remove', 'cancel']))
                                                    bg-red-100 text-red-600 dark:bg-red-800 dark:text-red-200
                                                @elseif(Str::contains($log->action, ['update', 'edit', 'modify']))
                                                    bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-200
                                                @else
                                                    bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-200
                                                @endif">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if(Str::contains($log->action, ['create', 'add', 'deliver']))
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    @elseif(Str::contains($log->action, ['delete', 'remove', 'cancel']))
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    @elseif(Str::contains($log->action, ['update', 'edit', 'modify']))
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    @endif
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $log->created_at->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-400 dark:text-gray-500">{{ $log->created_at->format('h:i A') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $log->user ? $log->user->name : 'System' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->user ? $log->user->email : '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if(Str::contains($log->action, ['create', 'add', 'deliver'])) 
                                                bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                            @elseif(Str::contains($log->action, ['delete', 'remove', 'cancel']))
                                                bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                            @elseif(Str::contains($log->action, ['update', 'edit', 'modify']))
                                                bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                            @else
                                                bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100
                                            @endif">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            @if ($log->action === 'login')
                                                User logged into the system
                                            @elseif ($log->action === 'logout')
                                                User logged out of the system
                                            @elseif ($log->action === 'status_changed' && $log->table_name === 'purchase_orders')
                                                @php
                                                    $newValues = is_array($log->new_values) 
                                                        ? $log->new_values 
                                                        : (is_string($log->new_values) ? json_decode($log->new_values, true) : []);
                                                    $specificAction = $newValues['action'] ?? null;
                                                    $newStatus = $newValues['status'] ?? null;
                                                @endphp
                                                @if ($specificAction === 'marked_as_delivered')
                                                    Marked Purchase Order #{{ $log->record_id }} as Delivered
                                                @elseif ($specificAction === 'marked_as_canceled')
                                                    Marked Purchase Order #{{ $log->record_id }} as Canceled
                                                @else
                                                    Changed Purchase Order #{{ $log->record_id }} status to {{ ucfirst($newStatus) }}
                                                @endif
                                            @else
                                                @php
                                                    $tableName = ucfirst(str_replace('_', ' ', $log->table_name));
                                                    $recordId = $log->record_id;
                                                @endphp
                                                {{ match($log->action) {
                                                    'created' => "Created new {$tableName} record #{$recordId}",
                                                    'updated' => "Updated {$tableName} record #{$recordId}",
                                                    'deleted' => "Deleted {$tableName} record #{$recordId}",
                                                    default => ucfirst($log->action) . " {$tableName} record #{$recordId}"
                                                } }}
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No recent activity found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('inventory.items.create') }}" class="flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                            <svg class="h-8 w-8 text-indigo-600 dark:text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Add New Item</span>
                        </a>
                        <a href="{{ route('inventory.purchase_orders.create') }}" class="flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                            <svg class="h-8 w-8 text-indigo-600 dark:text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Create Purchase Order</span>
                        </a>
                        <a href="{{ route('inventory.stock.out') }}" class="flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                            <svg class="h-8 w-8 text-indigo-600 dark:text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Assign Items</span>
                        </a>
                        <a href="{{ route('inventory.reports.index') }}" class="flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                            <svg class="h-8 w-8 text-indigo-600 dark:text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Generate Reports</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Category Distribution Chart
            fetch('/api/inventory/category-distribution')
                .then(response => response.json())
                .catch(() => {
                    // Fallback data for demo if the endpoint doesn't exist
                    return {
                        labels: ['Furniture', 'Electronics', 'Kitchen', 'Bathroom', 'Others'],
                        data: [45, 25, 15, 10, 5]
                    };
                })
                .then(data => {
                    const ctx = document.getElementById('categoryDistributionChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: data.labels || ['Furniture', 'Electronics', 'Kitchen', 'Bathroom', 'Others'],
                            datasets: [{
                                data: data.data || [45, 25, 15, 10, 5],
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(255, 206, 86, 0.8)',
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(153, 102, 255, 0.8)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                }
                            }
                        }
                    });
                });

            // Stock Movement Chart
            fetch('/api/inventory/stock-movements')
                .then(response => response.json())
                .catch(() => {
                    // Fallback data for demo if the endpoint doesn't exist
                    return {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        inData: [12, 19, 3, 5, 2, 3],
                        outData: [8, 12, 6, 9, 4, 7]
                    };
                })
                .then(data => {
                    const ctx = document.getElementById('stockMovementChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                            datasets: [
                                {
                                    label: 'Stock In',
                                    data: data.inData || [12, 19, 3, 5, 2, 3],
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    tension: 0.4,
                                    fill: true
                                },
                                {
                                    label: 'Stock Out',
                                    data: data.outData || [8, 12, 6, 9, 4, 7],
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    tension: 0.4,
                                    fill: true
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        title: function(tooltipItems) {
                                            return data.labels[tooltipItems[0].dataIndex];
                                        },
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            if (context.parsed.y !== null) {
                                                label += context.parsed.y + ' movements';
                                            }
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
        });
    </script>
@endsection