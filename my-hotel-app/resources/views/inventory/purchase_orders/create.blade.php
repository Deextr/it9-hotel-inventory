@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Create Purchase Order
                </h2>
                <a href="{{ route('inventory.purchase_orders.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center p-1 rounded-full hover:bg-gray-100" title="Back to Purchase Orders">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
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

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('inventory.purchase_orders.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
                                <select id="supplier_id" name="supplier_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                                <input type="date" name="order_date" id="order_date" value="{{ old('order_date', date('Y-m-d')) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                @error('order_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Item Selection Section -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Select Items</h3>
                            
                            <!-- Item Filtering -->
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                        <label for="filter-category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                        <select id="filter-category" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="all">All Categories</option>
                                            @foreach($items->pluck('category.name', 'category.id')->unique() as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    
                                        <div>
                                        <label for="filter-search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                        <input type="text" id="filter-search" placeholder="Item name..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Items Table -->
                            <div class="border rounded-lg overflow-hidden">
                                <div class="overflow-x-auto">
                                    <div class="overflow-y-auto max-h-96">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50 sticky top-0">
                                                <tr>
                                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        <input type="checkbox" id="select-all-items" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    </th>
                                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Item
                                                    </th>
                                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Category
                                                    </th>
                                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Description
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($items as $item)
                                                    @if($item->is_active)
                                                    <tr class="item-row hover:bg-gray-50" 
                                                        data-id="{{ $item->id }}" 
                                                        data-name="{{ $item->name }}" 
                                                        data-category="{{ $item->category->id ?? '' }}"
                                                        data-search="{{ strtolower($item->name . ' ' . ($item->description ?? '')) }}">
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <input type="checkbox" 
                                                                name="selected_items[]" 
                                                                value="{{ $item->id }}" 
                                                                class="item-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                                                data-id="{{ $item->id }}"
                                                                data-name="{{ $item->name }}">
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">{{ $item->category->name ?? 'N/A' }}</div>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <div class="text-sm text-gray-900 truncate max-w-xs">{{ $item->description ?: 'No description' }}</div>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                        </div>
                            
                            <div class="mt-2 flex justify-between items-center">
                                <div class="text-sm text-gray-700"><span id="selected-count">0</span> items selected</div>
                                        <div>
                                    <button type="button" id="clear-all" class="text-sm text-gray-600 hover:text-gray-900">Clear All</button>
                                </div>
                                        </div>
                                            </div>

                        <!-- Selected Items Section -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Order Details</h3>
                            <div class="border rounded-md p-4">
                                <div id="no-items-message" class="text-center py-8 text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                    <p class="mt-2">No items selected. Please select items from the table above.</p>
                                </div>
                                
                                <div id="order-details-table" class="hidden">
                                    <div class="overflow-x-auto">
                                        <div class="overflow-y-auto max-h-96">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50 sticky top-0">
                                                    <tr>
                                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="order-items" class="bg-white divide-y divide-gray-200">
                                                    <!-- Order items will be added here dynamically -->
                                                </tbody>
                                            </table>
                                        </div>
                                        <table class="min-w-full">
                                            <tfoot class="bg-gray-50">
                                                <tr>
                                                    <td colspan="3" class="px-4 py-3 text-right text-sm font-medium text-gray-700">Total:</td>
                                                    <td class="px-4 py-3 text-left text-sm font-bold text-gray-900 w-1/4" id="order-total">₱0.00</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" id="submit-button" class="text-white bg-indigo-600 hover:bg-indigo-700 flex items-center px-4 py-2 rounded-md font-medium" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Create Purchase Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cache DOM elements
            const selectAllCheckbox = document.getElementById('select-all-items');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const clearAllButton = document.getElementById('clear-all');
            const selectedCountElement = document.getElementById('selected-count');
            const noItemsMessage = document.getElementById('no-items-message');
            const orderDetailsTable = document.getElementById('order-details-table');
            const orderItemsContainer = document.getElementById('order-items');
            const orderTotalElement = document.getElementById('order-total');
            const submitButton = document.getElementById('submit-button');
            const filterCategorySelect = document.getElementById('filter-category');
            const filterSearchInput = document.getElementById('filter-search');
            
            // Item data store - will contain all selected items
            const selectedItems = new Map();
            
            // Initialize event listeners
            initEventListeners();
            
            function initEventListeners() {
                // Select all checkbox
                selectAllCheckbox.addEventListener('change', function() {
                    const isChecked = this.checked;
                    
                    // Get all visible item rows
                    const visibleItemRows = Array.from(document.querySelectorAll('.item-row'))
                        .filter(row => row.style.display !== 'none');
                    
                    // Check/uncheck all visible checkboxes
                    visibleItemRows.forEach(row => {
                        const checkbox = row.querySelector('.item-checkbox');
                        checkbox.checked = isChecked;
                        
                        const itemId = parseInt(checkbox.dataset.id);
                        const itemName = checkbox.dataset.name;
                        
                        if (isChecked) {
                            // Add to selected items if not already there
                            if (!selectedItems.has(itemId)) {
                                selectedItems.set(itemId, {
                                    id: itemId,
                                    name: itemName,
                        quantity: 1,
                                    unitPrice: 0,
                        subtotal: 0
                                });
                            }
                        } else {
                            // Remove from selected items
                            selectedItems.delete(itemId);
                        }
                    });
                    
                    // Update the UI
                    updateUI();
                });
                
                // Individual item checkboxes
                itemCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const itemId = parseInt(this.dataset.id);
                        const itemName = this.dataset.name;
                        
                        if (this.checked) {
                            // Add to selected items
                            selectedItems.set(itemId, {
                                id: itemId,
                                name: itemName,
                        quantity: 1,
                                unitPrice: 0,
                        subtotal: 0
                            });
                        } else {
                            // Remove from selected items
                            selectedItems.delete(itemId);
                        }
                        
                        // Update the UI
                        updateUI();
                    });
                });
                
                // Clear all button
                clearAllButton.addEventListener('click', function() {
                    // Uncheck all checkboxes
                    selectAllCheckbox.checked = false;
                    itemCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    
                    // Clear selected items
                    selectedItems.clear();
                    
                    // Update the UI
                    updateUI();
                });
                
                // Filter by category
                filterCategorySelect.addEventListener('change', function() {
                    filterItems();
                });
                
                // Filter by search
                filterSearchInput.addEventListener('input', function() {
                    filterItems();
                });
            }
            
            function filterItems() {
                const categoryFilter = filterCategorySelect.value;
                const searchFilter = filterSearchInput.value.toLowerCase();
                
                const rows = document.querySelectorAll('.item-row');
                
                rows.forEach(row => {
                    const categoryMatch = categoryFilter === 'all' || row.dataset.category === categoryFilter;
                    const searchMatch = !searchFilter || row.dataset.search.includes(searchFilter);
                    
                    if (categoryMatch && searchMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Update select all checkbox state
                updateSelectAllCheckboxState();
            }
            
            function updateSelectAllCheckboxState() {
                const visibleRows = Array.from(document.querySelectorAll('.item-row'))
                    .filter(row => row.style.display !== 'none');
                
                if (visibleRows.length === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                    return;
                }
                
                const allChecked = visibleRows.every(row => {
                    return row.querySelector('.item-checkbox').checked;
                });
                
                const someChecked = visibleRows.some(row => {
                    return row.querySelector('.item-checkbox').checked;
                });
                
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = !allChecked && someChecked;
            }
            
            function updateUI() {
                // Update selected count
                selectedCountElement.textContent = selectedItems.size;
                
                // Show/hide no items message and order details table
                if (selectedItems.size === 0) {
                    noItemsMessage.classList.remove('hidden');
                    orderDetailsTable.classList.add('hidden');
                    submitButton.disabled = true;
                } else {
                    noItemsMessage.classList.add('hidden');
                    orderDetailsTable.classList.remove('hidden');
                    submitButton.disabled = false;
                }
                
                // Update order items table
                renderOrderItems();
                
                // Update select all checkbox state
                updateSelectAllCheckboxState();
            }
            
            function renderOrderItems() {
                // Clear existing items
                orderItemsContainer.innerHTML = '';
                
                // Add each selected item
                let total = 0;
                
                selectedItems.forEach((item, itemId) => {
                    const row = document.createElement('tr');
                    
                    // Item name cell
                    const nameCell = document.createElement('td');
                    nameCell.className = 'px-4 py-3 whitespace-nowrap';
                    
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `items[${itemId}][item_id]`;
                    hiddenInput.value = itemId;
                    
                    const nameDiv = document.createElement('div');
                    nameDiv.className = 'text-sm font-medium text-gray-900';
                    nameDiv.textContent = item.name;
                    
                    nameCell.appendChild(hiddenInput);
                    nameCell.appendChild(nameDiv);
                    
                    // Quantity cell
                    const quantityCell = document.createElement('td');
                    quantityCell.className = 'px-4 py-3 whitespace-nowrap';
                    
                    const quantityInput = document.createElement('input');
                    quantityInput.type = 'number';
                    quantityInput.name = `items[${itemId}][quantity]`;
                    quantityInput.value = item.quantity;
                    quantityInput.min = '1';
                    quantityInput.className = 'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md';
                    quantityInput.required = true;
                    
                    quantityInput.addEventListener('input', function() {
                        item.quantity = parseFloat(this.value) || 0;
                        updateItemSubtotal(item);
                        updateOrderTotal();
                    });
                    
                    quantityCell.appendChild(quantityInput);
                    
                    // Unit price cell
                    const priceCell = document.createElement('td');
                    priceCell.className = 'px-4 py-3 whitespace-nowrap';
                    
                    const priceInput = document.createElement('input');
                    priceInput.type = 'number';
                    priceInput.name = `items[${itemId}][unit_price]`;
                    priceInput.value = item.unitPrice;
                    priceInput.min = '0';
                    priceInput.step = '0.01';
                    priceInput.className = 'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md';
                    priceInput.required = true;
                    
                    priceInput.addEventListener('input', function() {
                        item.unitPrice = parseFloat(this.value) || 0;
                        updateItemSubtotal(item);
                        updateOrderTotal();
                    });
                    
                    priceCell.appendChild(priceInput);
                    
                    // Subtotal cell
                    const subtotalCell = document.createElement('td');
                    subtotalCell.className = 'px-4 py-3 whitespace-nowrap w-1/4';
                    
                    const subtotalDiv = document.createElement('div');
                    subtotalDiv.className = 'text-sm font-medium text-gray-900';
                    subtotalDiv.textContent = formatCurrency(item.subtotal);
                    subtotalDiv.dataset.itemId = itemId;
                    
                    subtotalCell.appendChild(subtotalDiv);
                    
                    // Add cells to row
                    row.appendChild(nameCell);
                    row.appendChild(quantityCell);
                    row.appendChild(priceCell);
                    row.appendChild(subtotalCell);
                    
                    // Add row to table
                    orderItemsContainer.appendChild(row);
                    
                    // Add to total
                    total += item.subtotal;
                });
                
                // Update order total
                orderTotalElement.textContent = formatCurrency(total);
            }
            
            function updateItemSubtotal(item) {
                item.subtotal = item.quantity * item.unitPrice;
                
                // Update the subtotal display if it exists
                const subtotalElement = document.querySelector(`[data-item-id="${item.id}"]`);
                if (subtotalElement) {
                    subtotalElement.textContent = formatCurrency(item.subtotal);
                }
            }
            
            function updateOrderTotal() {
                let total = 0;
                selectedItems.forEach(item => {
                    total += item.subtotal;
                });
                
                orderTotalElement.textContent = formatCurrency(total);
            }
            
            function formatCurrency(value) {
                return '₱' + parseFloat(value || 0).toFixed(2);
            }
        });
    </script>
@endsection
