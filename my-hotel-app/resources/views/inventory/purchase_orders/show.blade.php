@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Purchase Order #{{ $purchaseOrder->id }}
                </h2>
                <div class="flex space-x-2">
                    @if($purchaseOrder->status === 'pending')
                        <a href="{{ route('inventory.purchase_orders.edit', $purchaseOrder) }}" class="btn-primary">
                            Edit
                        </a>
                    @endif
                    
                    @if($purchaseOrder->status === 'delivered')
                        <a href="{{ route('inventory.view') }}" class="btn-primary">
                            View Inventory
                        </a>
                    @endif
                    
                    <a href="{{ route('inventory.purchase_orders.index') }}" class="btn-secondary">
                        Back to Purchase Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="py-12">
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
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Purchase Order Details</h3>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Order ID</p>
                                        <p class="mt-1">{{ $purchaseOrder->id }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Order Date</p>
                                        <p class="mt-1">{{ $purchaseOrder->order_date->format('Y-m-d') }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-sm font-medium text-gray-500">Status</p>
                                        <div class="mt-1 flex items-center space-x-4">
                                            @if ($purchaseOrder->status == 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                                <div class="flex space-x-2">
                                                    <form action="{{ route('inventory.purchase_orders.deliver', $purchaseOrder) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" style="background-color: #10b981; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-weight: 500; border: none;" onclick="return confirm('Are you sure you want to mark this order as delivered? Items will be automatically added to inventory.')">
                                                            Mark as Delivered
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('inventory.purchase_orders.cancel', $purchaseOrder) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" style="background-color: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-weight: 500; border: none;" onclick="return confirm('Are you sure you want to cancel this order?')">
                                                            Cancel Order
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif ($purchaseOrder->status == 'delivered')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Delivered
                                                </span>
                                                <span class="text-sm text-green-600">
                                                    Items have been added to inventory
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Canceled
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($purchaseOrder->status == 'delivered' && $purchaseOrder->delivered_date)
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Delivered Date</p>
                                            <p class="mt-1">{{ $purchaseOrder->delivered_date->format('Y-m-d') }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Total Amount</p>
                                        <p class="mt-1">₱{{ number_format($purchaseOrder->total_amount, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Supplier Information</h3>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Supplier Name</p>
                                        <p class="mt-1">{{ $purchaseOrder->supplier->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Contact Person</p>
                                        <p class="mt-1">{{ $purchaseOrder->supplier->contact_person }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Contact Information</p>
                                        <p class="mt-1">{{ $purchaseOrder->supplier->email }} | {{ $purchaseOrder->supplier->phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($purchaseOrder->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->item_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">₱{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">₱{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center">No items found.</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-gray-50">
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right font-medium">Total:</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold">₱{{ number_format($purchaseOrder->total_amount, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

