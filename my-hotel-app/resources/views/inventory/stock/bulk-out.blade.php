@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Bulk Assign Items to Locations
                </h2>
                <div class="flex space-x-3">
                    <a href="{{ route('inventory.stock.out') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Single Assignment
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
                            <li>{!! $error !!}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800">
                                    This page allows you to assign multiple items to multiple rooms at once. For example, you can assign beds, pillows, and chairs to rooms 101-110 on floor 1.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Bulk Assignment Form</h3>
                    
                    <form action="{{ route('inventory.stock.bulk-out.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Left Column: Items Selection -->
                            <div>
                                <h4 class="text-md font-medium text-gray-800 mb-3">1. Select Items</h4>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="mb-3">
                                        <label for="item-search" class="block text-sm font-medium text-gray-700 mb-1">Search Items</label>
                                        <input type="text" id="item-search" placeholder="Type to search items..." 
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    
                                    <div class="overflow-y-auto max-h-96 border border-gray-300 rounded-md p-2 bg-white">
                                        <div class="space-y-2">
                                            @foreach($items as $item)
                                                <div class="item-entry" data-search="{{ strtolower($item->name . ' ' . $item->sku) }}">
                                                    <label class="flex items-start p-2 rounded border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                                        <input type="checkbox" name="items[]" value="{{ $item->id }}" 
                                                               class="item-checkbox mt-1 h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                                               data-stock="{{ $item->getCurrentStock() }}"
                                                               {{ in_array($item->id, old('items', [])) ? 'checked' : '' }}>
                                                        <div class="ml-2 text-sm">
                                                            <p class="font-medium text-gray-700">{{ $item->name }}</p>
                                                            <p class="text-gray-500">Current Stock: {{ $item->getCurrentStock() }}</p>
                                                            @if ($item->sku)
                                                                <p class="text-gray-500">SKU: {{ $item->sku }}</p>
                                                            @endif
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2 flex justify-between items-center">
                                        <div class="text-sm text-gray-700"><span id="selected-items-count">0</span> items selected</div>
                                        <div>
                                            <button type="button" id="select-all-items" class="text-sm text-indigo-600 hover:text-indigo-900">Select All Visible</button>
                                            <button type="button" id="clear-all-items" class="text-sm text-gray-600 hover:text-gray-900 ml-4">Clear All</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Column: Location Selection -->
                            <div>
                                <h4 class="text-md font-medium text-gray-800 mb-3">2. Select Target Locations</h4>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <div>
                                            <label for="floor_number" class="block text-sm font-medium text-gray-700 mb-1">Floor *</label>
                                            <select name="floor_number" id="floor_number" 
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                   required>
                                                <option value="">Select Floor</option>
                                                @foreach($floors as $floor)
                                                    <option value="{{ $floor }}" {{ old('floor_number') == $floor ? 'selected' : '' }}>
                                                        Floor {{ $floor }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label for="area_type" class="block text-sm font-medium text-gray-700 mb-1">Area Type *</label>
                                            <select name="area_type" id="area_type" 
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                   required>
                                                <option value="">Select Area Type</option>
                                                @foreach($areaTypes as $area)
                                                    <option value="{{ $area['value'] }}" {{ old('area_type') == $area['value'] ? 'selected' : '' }}>
                                                        {{ $area['label'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <p class="text-xs text-gray-500 mb-2">Specify the range of room numbers to assign items to</p>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label for="room_start" class="block text-sm font-medium text-gray-700 mb-1">Room Number Start</label>
                                                <select name="room_start" id="room_start" 
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    <option value="">Select Start Room</option>
                                                    <!-- Will be populated by JavaScript based on floor and area type -->
                                                </select>
                                            </div>
                                            
                                            <div>
                                                <label for="room_end" class="block text-sm font-medium text-gray-700 mb-1">Room Number End</label>
                                                <select name="room_end" id="room_end" 
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    <option value="">Select End Room</option>
                                                    <!-- Will be populated by JavaScript based on floor and area type -->
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="use_room_range" id="use_room_range" value="1">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div id="preview-area" class="border border-gray-300 rounded-md p-3 bg-white">
                                            <p class="text-sm text-gray-700" id="preview-text">Complete the form to see a preview of affected locations.</p>
                                            <div id="preview-list" class="mt-2 text-sm"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quantity and Notes -->
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-md font-medium text-gray-800 mb-3">3. Specify Quantities Per Item and Location</h4>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div id="location-quantities-container" class="space-y-4">
                                        <p class="text-sm text-gray-700 mb-2">First select items, floor, area type, and room range to configure quantities</p>
                                        <!-- Dynamic content will be generated here by JavaScript -->
                                    </div>
                                    <p class="text-xs text-red-500 mt-1 hidden" id="stock-warning">Warning: You may not have enough stock for all selected items and locations</p>
                                </div>
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                <textarea name="notes" id="notes" rows="3" 
                                         class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Optional notes about this bulk assignment</p>
                            </div>
                        </div>
                        
                        <!-- Submit -->
                        <div>
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 hidden" id="confirmation-area">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700" id="confirmation-text">
                                            You are about to assign items to multiple locations. Please review the details below.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" 
                                   class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Bulk Assign Items to Locations
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Item selection elements
            const itemSearch = document.getElementById('item-search');
            const itemEntries = document.querySelectorAll('.item-entry');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const selectAllItemsButton = document.getElementById('select-all-items');
            const clearAllItemsButton = document.getElementById('clear-all-items');
            const selectedItemsCount = document.getElementById('selected-items-count');
            
            // Location selection elements
            const floorSelect = document.getElementById('floor_number');
            const areaTypeSelect = document.getElementById('area_type');
            const useRoomRange = document.getElementById('use_room_range');
            const roomStartSelect = document.getElementById('room_start');
            const roomEndSelect = document.getElementById('room_end');
            const previewText = document.getElementById('preview-text');
            const previewList = document.getElementById('preview-list');
            const locationQuantitiesContainer = document.getElementById('location-quantities-container');
            
            // Quantity and confirmation elements
            const stockWarning = document.getElementById('stock-warning');
            const confirmationArea = document.getElementById('confirmation-area');
            const confirmationText = document.getElementById('confirmation-text');
            
            // Track selected locations and items
            let selectedLocations = [];
            let selectedItems = [];
            
            // Fetch room numbers when floor or area type changes
            floorSelect.addEventListener('change', updateRoomNumbers);
            areaTypeSelect.addEventListener('change', updateRoomNumbers);
            
            // Room start/end changes should update the preview and location quantities
            roomStartSelect.addEventListener('change', function() {
                updatePreview();
                generateLocationQuantityInputs();
            });
            
            roomEndSelect.addEventListener('change', function() {
                updatePreview();
                generateLocationQuantityInputs();
            });
            
            // Fetch room numbers for the selected floor and area
            function updateRoomNumbers() {
                const floor = floorSelect.value;
                const areaType = areaTypeSelect.value;
                
                if (!floor || !areaType) {
                    roomStartSelect.innerHTML = '<option value="">Select Start Room</option>';
                    roomEndSelect.innerHTML = '<option value="">Select End Room</option>';
                    return;
                }
                
                // Fetch room numbers via AJAX
                fetch(`/api/locations/rooms?floor=${floor}&area=${areaType}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate room start dropdown
                        roomStartSelect.innerHTML = '<option value="">Select Start Room</option>';
                        roomEndSelect.innerHTML = '<option value="">Select End Room</option>';
                        
                        if (data.rooms && data.rooms.length > 0) {
                            data.rooms.forEach(room => {
                                roomStartSelect.innerHTML += `<option value="${room}">${room}</option>`;
                                roomEndSelect.innerHTML += `<option value="${room}">${room}</option>`;
                            });
                        } else {
                            previewText.textContent = 'No rooms found for the selected criteria.';
                        }
                        
                        updatePreview();
                        generateLocationQuantityInputs();
                    })
                    .catch(error => {
                        console.error('Error fetching room numbers:', error);
                    });
            }
            
            // Update preview of selected locations
            function updatePreview() {
                const floor = floorSelect.value;
                const areaType = areaTypeSelect.value;
                
                if (!floor || !areaType) {
                    previewText.textContent = 'Please select floor and area type to see affected locations.';
                    previewList.innerHTML = '';
                    selectedLocations = [];
                    return;
                }
                
                const roomStart = roomStartSelect.value;
                const roomEnd = roomEndSelect.value;
                
                if (!roomStart || !roomEnd) {
                    previewText.textContent = 'Please select start and end room numbers.';
                    previewList.innerHTML = '';
                    selectedLocations = [];
                    return;
                }
                
                // Fetch actual locations in the specified range (to get actual location IDs)
                fetch(`/api/locations?floor=${floor}&area=${areaType}&room_start=${roomStart}&room_end=${roomEnd}`)
                    .then(response => response.json())
                    .then(data => {
                        selectedLocations = data.locations || [];
                        
                        if (selectedLocations.length === 0) {
                            previewText.textContent = 'No locations found in the selected range.';
                            previewList.innerHTML = '';
                            return;
                        }
                        
                        // Show preview of selected locations
                        previewText.textContent = 'Selected Locations:';
                        
                        // Display the first 5 locations and a count if there are more
                        const previewLocations = selectedLocations.slice(0, 5);
                        let previewHTML = '<div class="p-2 bg-gray-50 rounded">';
                        
                        previewLocations.forEach(location => {
                            previewHTML += `<div>${location.name} ${location.room_number ? '- Room ' + location.room_number : ''}</div>`;
                        });
                        
                        if (selectedLocations.length > 5) {
                            previewHTML += `<div class="text-gray-500">...and ${selectedLocations.length - 5} more locations</div>`;
                        }
                        
                        previewHTML += `<div class="mt-2 font-semibold">Total: ${selectedLocations.length} locations</div>`;
                        previewHTML += '</div>';
                        
                        previewList.innerHTML = previewHTML;
                        
                        // Generate quantity inputs for each location and item
                        generateLocationQuantityInputs();
                    })
                    .catch(error => {
                        console.error('Error fetching locations:', error);
                    });
            }
            
            // Generate quantity inputs for each location and selected item
            function generateLocationQuantityInputs() {
                // Get selected items
                selectedItems = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(checkbox => {
                    const itemId = checkbox.value;
                    const itemName = checkbox.closest('.item-entry').querySelector('.font-medium').textContent;
                    const itemStock = parseInt(checkbox.dataset.stock);
                    return { id: itemId, name: itemName, stock: itemStock };
                });
                
                // If no items or locations selected, clear the container
                if (selectedItems.length === 0 || selectedLocations.length === 0) {
                    locationQuantitiesContainer.innerHTML = '<p class="text-sm text-gray-700 mb-2">First select items, floor, area type, and room range to configure quantities</p>';
                    return;
                }
                
                // Generate inputs for each location with all selected items
                let html = '';
                
                html += `
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-3">
                            <h5 class="font-medium">Set quantities for each location:</h5>
                            <button type="button" id="set-all-quantities" class="text-sm text-indigo-600 hover:text-indigo-900 px-3 py-1 border border-indigo-300 rounded-md">
                                Set Default for All
                            </button>
                        </div>
                        <div id="default-quantities" class="bg-blue-50 p-3 rounded-md mb-4">
                            <p class="text-sm text-gray-700 mb-2">Set default quantities to apply to all locations:</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                ${selectedItems.map(item => `
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">${item.name}</label>
                                        <div class="flex items-center mt-1">
                                            <input type="number" id="default-qty-${item.id}" min="0" value="1" 
                                                   class="default-qty w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                   data-item-id="${item.id}">
                                            <span class="ml-2 text-xs text-gray-500">Stock: ${item.stock}</span>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;
                
                html += '<div class="space-y-4">';
                
                // Create accordion for each location
                selectedLocations.forEach((location, index) => {
                    const isFirst = index === 0;
                    html += `
                        <div class="location-section border border-gray-200 rounded-md overflow-hidden">
                            <div class="location-header flex justify-between items-center bg-gray-50 px-4 py-2 cursor-pointer" 
                                 data-location-id="${location.id}">
                                <h6 class="font-semibold">${location.name} ${location.room_number ? '- Room ' + location.room_number : ''}</h6>
                                <svg class="w-5 h-5 transform transition-transform ${isFirst ? 'rotate-180' : ''}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div class="location-content p-4 bg-white ${isFirst ? '' : 'hidden'}">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    ${selectedItems.map(item => `
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">${item.name}</label>
                                            <div class="flex items-center mt-1">
                                                <input type="number" name="location_quantities[${location.id}][${item.id}]" 
                                                       min="0" value="1" 
                                                       class="quantity-input w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                       data-location-id="${location.id}"
                                                       data-item-id="${item.id}">
                                                <span class="ml-2 text-xs text-gray-500">Stock: ${item.stock}</span>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                
                locationQuantitiesContainer.innerHTML = html;
                
                // Add event listeners for accordion headers
                document.querySelectorAll('.location-header').forEach(header => {
                    header.addEventListener('click', function() {
                        const content = this.nextElementSibling;
                        const arrow = this.querySelector('svg');
                        
                        content.classList.toggle('hidden');
                        arrow.classList.toggle('rotate-180');
                    });
                });
                
                // Add event listener for set all quantities button
                document.getElementById('set-all-quantities')?.addEventListener('click', function() {
                    // Get all default values
                    const defaultValues = {};
                    document.querySelectorAll('.default-qty').forEach(input => {
                        const itemId = input.dataset.itemId;
                        const value = parseInt(input.value);
                        defaultValues[itemId] = value;
                    });
                    
                    // Set values to all location quantity inputs
                    document.querySelectorAll('.quantity-input').forEach(input => {
                        const itemId = input.dataset.itemId;
                        if (defaultValues[itemId] !== undefined) {
                            input.value = defaultValues[itemId];
                        }
                    });
                });
            }
            
            // Item selection handling
            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    // Update selected items count
                    updateSelectedItemCount();
                    // Generate quantity inputs with updated item selection
                    generateLocationQuantityInputs();
                });
            });
            
            // Form submission handling
            document.querySelector('form').addEventListener('submit', function(e) {
                const selectedItemsCount = document.querySelectorAll('.item-checkbox:checked').length;
                if (selectedItemsCount === 0) {
                    e.preventDefault();
                    alert('Please select at least one item to assign.');
                    return false;
                }
                
                if (!floorSelect.value || !areaTypeSelect.value) {
                    e.preventDefault();
                    alert('Please select floor and area type.');
                    return false;
                }
                
                if (!roomStartSelect.value || !roomEndSelect.value) {
                    e.preventDefault();
                    alert('Please select start and end room numbers.');
                    return false;
                }
                
                // Validate stock availability
                let insufficientStock = false;
                const stockUsage = {};
                
                // Initialize stock usage tracking
                selectedItems.forEach(item => {
                    stockUsage[item.id] = { available: item.stock, used: 0 };
                });
                
                // Calculate total usage across all locations
                document.querySelectorAll('.quantity-input').forEach(input => {
                    const itemId = input.dataset.itemId;
                    const qty = parseInt(input.value) || 0;
                    
                    if (stockUsage[itemId]) {
                        stockUsage[itemId].used += qty;
                    }
                });
                
                // Check if any item exceeds available stock
                for (const itemId in stockUsage) {
                    if (stockUsage[itemId].used > stockUsage[itemId].available) {
                        insufficientStock = true;
                        const itemName = selectedItems.find(item => item.id === itemId)?.name || 'Item';
                        alert(`Insufficient stock for ${itemName}. Available: ${stockUsage[itemId].available}, Required: ${stockUsage[itemId].used}`);
                        break;
                    }
                }
                
                if (insufficientStock) {
                    e.preventDefault();
                    return false;
                }
                
                return true;
            });
            
            // Filter items based on search
            function filterItems() {
                const searchValue = itemSearch.value.toLowerCase();
                
                itemEntries.forEach(item => {
                    const searchText = item.dataset.search;
                    const isVisible = searchValue === '' || searchText.includes(searchValue);
                    item.style.display = isVisible ? '' : 'none';
                });
            }
            
            // Update selected items count
            function updateSelectedItemCount() {
                const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
                selectedItemsCount.textContent = checkedCount;
            }
            
            // Initial setup
            updateRoomNumbers();
            updateSelectedItemCount();
            
            // Setup search functionality
            itemSearch.addEventListener('input', filterItems);
            
            // Setup select/clear all functionality
            selectAllItemsButton.addEventListener('click', function() {
                itemEntries.forEach(entry => {
                    if (entry.style.display !== 'none') {
                        const checkbox = entry.querySelector('.item-checkbox');
                        checkbox.checked = true;
                    }
                });
                updateSelectedItemCount();
                generateLocationQuantityInputs();
            });
            
            clearAllItemsButton.addEventListener('click', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateSelectedItemCount();
                generateLocationQuantityInputs();
            });
        });
    </script>
@endsection 