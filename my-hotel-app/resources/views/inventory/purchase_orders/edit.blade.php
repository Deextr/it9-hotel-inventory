@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Purchase Order #{{ $purchaseOrder->id }}
                </h2>
                <div class="flex space-x-2">
                    <a href="{{ route('inventory.purchase_orders.show', $purchaseOrder) }}" class="btn-secondary">
                        Cancel
                    </a>
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
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('inventory.purchase_orders.update', $purchaseOrder) }}" method="POST" 
                          x-data="purchaseOrderForm({{ json_encode($purchaseOrder->items) }})">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
                                <select id="supplier_id" name="supplier_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchaseOrder->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="order_date" class="block text-sm font-medium text-gray-700">Order Date</label>
                                <input type="date" name="order_date" id="order_date" value="{{ old('order_date', $purchaseOrder->order_date->format('Y-m-d')) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                @error('order_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="pending" {{ old('status', $purchaseOrder->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="delivered" {{ old('status', $purchaseOrder->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="canceled" {{ old('status', $purchaseOrder->status) == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Order Items</h3>
                            <div class="border rounded-md p-4">
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 pb-4 border-b border-gray-200">
                                        <div>
                                            <label :for="'items['+index+'][item_name]'" class="block text-sm font-medium text-gray-700">Item Name</label>
                                            <input type="text" :name="'items['+index+'][item_name]'" x-model="item.item_name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                            <input type="hidden" :name="'items['+index+'][id]'" x-model="item.id">
                                        </div>
                                        <div>
                                            <label :for="'items['+index+'][quantity]'" class="block text-sm font-medium text-gray-700">Quantity</label>
                                            <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required @input="calculateSubtotal(index)">
                                        </div>
                                        <div>
                                            <label :for="'items['+index+'][unit_price]'" class="block text-sm font-medium text-gray-700">Unit Price</label>
                                            <input type="number" :name="'items['+index+'][unit_price]'" x-model.number="item.unit_price" min="0" step="0.01" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required @input="calculateSubtotal(index)">
                                        </div>
                                        <div class="flex items-end">
                                            <div class="flex-grow">
                                                <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                                                <div class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-100 rounded-md shadow-sm sm:text-sm" x-text="formatCurrency(item.subtotal)"></div>
                                            </div>
                                            <button type="button" class="ml-2 mb-2 text-red-600 hover:text-red-900" @click="removeItem(index)" x-show="items.length > 1">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <div class="flex justify-between items-center mt-4">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" @click="addItem">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add Item
                                    </button>
                                    <div class="text-right">
                                        <span class="text-sm font-medium text-gray-700">Total Amount:</span>
                                        <span class="ml-2 text-lg font-bold text-gray-900" x-text="formatCurrency(calculateTotal())"></span>
                                    </div>
                                </div>
                            </div>
                            @error('items')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">
                                Update Purchase Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function purchaseOrderForm(existingItems) {
            return {
                items: existingItems.map(item => ({
                    id: item.id,
                    item_name: item.item_name,
                    quantity: parseFloat(item.quantity),
                    unit_price: parseFloat(item.unit_price),
                    subtotal: parseFloat(item.subtotal)
                })),
                addItem() {
                    this.items.push({
                        id: null,
                        item_name: '',
                        quantity: 1,
                        unit_price: 0,
                        subtotal: 0
                    });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                calculateSubtotal(index) {
                    const item = this.items[index];
                    item.subtotal = item.quantity * item.unit_price;
                },
                calculateTotal() {
                    return this.items.reduce((total, item) => total + (item.quantity * item.unit_price), 0);
                },
                formatCurrency(value) {
                    return new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD'
                    }).format(value);
                }
            };
        }
    </script>
@endsection
