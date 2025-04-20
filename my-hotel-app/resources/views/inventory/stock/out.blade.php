@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Assign Items to Locations
                </h2>
                <div class="flex space-x-3">
                    <a href="{{ route('inventory.stock.bulk-out') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Bulk Assignment
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
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Assign Item to Location</h3>
                    
                    <form action="{{ route('inventory.stock.out.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Item Selection -->
                            <div>
                                <label for="item_id" class="block text-sm font-medium text-gray-700 mb-1">Select Item *</label>
                                <select name="item_id" id="item_id" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       required>
                                    <option value="">-- Select an item --</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" 
                                                data-stock="{{ $item->getCurrentStock() }}"
                                                {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }} (Stock: {{ $item->getCurrentStock() }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Select the item to assign to location(s)</p>
                            </div>
                            
                            <!-- Default Quantity -->
                            <div>
                                <label for="default_quantity" class="block text-sm font-medium text-gray-700 mb-1">Default Quantity Per Location</label>
                                <input type="number" id="default_quantity" min="1" value="{{ old('default_quantity', 1) }}" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <p class="text-xs text-gray-500 mt-1">Default quantity to apply to all selected locations (can be customized per location)</p>
                                <p class="text-xs text-red-500 mt-1 hidden" id="stock-warning">Warning: You don't have enough stock for all selected locations</p>
                            </div>
                        </div>
                        
                        <!-- Location Filtering -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-md font-medium text-gray-700 mb-3">Filter Locations</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="filter-floor" class="block text-sm font-medium text-gray-700 mb-1">Floor</label>
                                    <select id="filter-floor" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="all">All Floors</option>
                                        @foreach($locations->keys() as $floor)
                                            <option value="{{ $floor }}">Floor {{ $floor }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="filter-area" class="block text-sm font-medium text-gray-700 mb-1">Area Type</label>
                                    <select id="filter-area" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="all">All Areas</option>
                                        @foreach($areaTypes as $area)
                                            <option value="{{ $area }}">{{ ucfirst($area) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="filter-search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                    <input type="text" id="filter-search" placeholder="Room number, name..." 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Location Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Location(s) and Specify Quantities *</label>
                            <div class="overflow-y-auto max-h-96 border border-gray-300 rounded-md p-2">
                                @foreach($locations as $floor => $floorLocations)
                                    <div class="location-floor mb-4" data-floor="{{ $floor }}">
                                        <h4 class="font-semibold text-gray-800 mb-2 bg-gray-100 p-2 rounded">Floor {{ $floor }}</h4>
                                        
                                        @foreach($floorLocations->groupBy('area_type') as $areaType => $areaLocations)
                                            <div class="location-area mb-3 ml-3" data-area="{{ $areaType }}">
                                                <h5 class="font-medium text-gray-700 mb-1">{{ ucfirst($areaType) }}</h5>
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2">
                                                    @foreach($areaLocations as $location)
                                                        <div class="location-item" data-search="{{ strtolower($location->name . ' ' . $location->room_number) }}">
                                                            <div class="p-2 rounded border border-gray-200 hover:bg-gray-50">
                                                                <div class="flex items-center">
                                                                    <input type="checkbox" name="locations[]" value="{{ $location->id }}" 
                                                                           class="location-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                                                           {{ in_array($location->id, old('locations', [])) ? 'checked' : '' }}
                                                                           data-location-id="{{ $location->id }}">
                                                                    <div class="ml-2 text-sm">
                                                                        <p class="font-medium text-gray-700">{{ $location->name }}</p>
                                                                        @if ($location->room_number)
                                                                            <p class="text-gray-500">Room: {{ $location->room_number }}</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="mt-2 quantity-input-container hidden">
                                                                    <label for="location_qty_{{ $location->id }}" class="block text-xs font-medium text-gray-700">Quantity:</label>
                                                                    <input type="number" name="location_quantities[{{ $location->id }}]" 
                                                                           id="location_qty_{{ $location->id }}" 
                                                                           class="location-quantity mt-1 block w-full py-1 px-2 text-sm border-gray-300 rounded-md" 
                                                                           min="1" value="{{ old('location_quantities.' . $location->id, 1) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-2 flex justify-between items-center">
                                <div class="text-sm text-gray-700"><span id="selected-count">0</span> locations selected</div>
                                <div>
                                    <button type="button" id="select-all" class="text-sm text-indigo-600 hover:text-indigo-900">Select All Visible</button>
                                    <button type="button" id="clear-all" class="text-sm text-gray-600 hover:text-gray-900 ml-4">Clear All</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" id="notes" rows="3" 
                                     class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Optional notes about this assignment</p>
                        </div>
                        
                        <!-- Submit -->
                        <div class="pt-4">
                            <button type="submit" 
                                   class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Assign Item to Selected Locations
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const floorFilter = document.getElementById('filter-floor');
            const areaFilter = document.getElementById('filter-area');
            const searchFilter = document.getElementById('filter-search');
            const selectAllButton = document.getElementById('select-all');
            const clearAllButton = document.getElementById('clear-all');
            const locationCheckboxes = document.querySelectorAll('.location-checkbox');
            const selectedCountElement = document.getElementById('selected-count');
            const itemSelect = document.getElementById('item_id');
            const defaultQuantityInput = document.getElementById('default_quantity');
            const stockWarning = document.getElementById('stock-warning');
            
            // Apply filters to locations
            function applyFilters() {
                const floorValue = floorFilter.value;
                const areaValue = areaFilter.value;
                const searchValue = searchFilter.value.toLowerCase();
                
                // Get all location floors
                const locationFloors = document.querySelectorAll('.location-floor');
                
                // Loop through floors
                locationFloors.forEach(floor => {
                    const floorNum = floor.dataset.floor;
                    let floorVisible = floorValue === 'all' || floorValue === floorNum;
                    let hasVisibleAreas = false;
                    
                    // Get all areas in this floor
                    const areas = floor.querySelectorAll('.location-area');
                    
                    // Loop through areas
                    areas.forEach(area => {
                        const areaType = area.dataset.area;
                        let areaVisible = areaValue === 'all' || areaValue === areaType;
                        let hasVisibleItems = false;
                        
                        // Get all location items in this area
                        const items = area.querySelectorAll('.location-item');
                        
                        // Loop through items
                        items.forEach(item => {
                            const searchText = item.dataset.search;
                            const isVisible = (searchValue === '' || searchText.includes(searchValue)) && areaVisible && floorVisible;
                            
                            item.style.display = isVisible ? '' : 'none';
                            
                            if (isVisible) {
                                hasVisibleItems = true;
                            }
                        });
                        
                        // Show/hide the area based on visibility of items
                        area.style.display = hasVisibleItems ? '' : 'none';
                        
                        if (hasVisibleItems) {
                            hasVisibleAreas = true;
                        }
                    });
                    
                    // Show/hide the floor based on visibility of areas
                    floor.style.display = hasVisibleAreas ? '' : 'none';
                });
                
                updateSelectedCount();
            }
            
            // Update selected locations count
            function updateSelectedCount() {
                const checkedCount = document.querySelectorAll('.location-checkbox:checked').length;
                selectedCountElement.textContent = checkedCount;
                updateStockWarning();
            }
            
            // Update stock warning message
            function updateStockWarning() {
                if (!itemSelect.value) {
                    stockWarning.classList.add('hidden');
                    return;
                }
                
                const selectedOption = itemSelect.options[itemSelect.selectedIndex];
                const availableStock = parseInt(selectedOption.dataset.stock);
                
                let totalNeeded = 0;
                document.querySelectorAll('.location-checkbox:checked').forEach(checkbox => {
                    const locationId = checkbox.dataset.locationId;
                    const quantityInput = document.getElementById(`location_qty_${locationId}`);
                    if (quantityInput) {
                        totalNeeded += parseInt(quantityInput.value || 1);
                    }
                });
                
                if (totalNeeded > availableStock) {
                    stockWarning.textContent = `Warning: Not enough stock. Available: ${availableStock}, Needed: ${totalNeeded}`;
                    stockWarning.classList.remove('hidden');
                } else {
                    stockWarning.classList.add('hidden');
                }
            }
            
            // Show/hide quantity inputs based on checkbox state
            function toggleQuantityInputs() {
                document.querySelectorAll('.location-checkbox').forEach(checkbox => {
                    const container = checkbox.closest('.location-item').querySelector('.quantity-input-container');
                    if (checkbox.checked) {
                        container.classList.remove('hidden');
                    } else {
                        container.classList.add('hidden');
                    }
                });
                updateStockWarning();
            }
            
            // Handle default quantity changes
            defaultQuantityInput.addEventListener('change', function() {
                const defaultValue = this.value;
                document.querySelectorAll('.location-quantity').forEach(input => {
                    input.value = defaultValue;
                });
                updateStockWarning();
            });
            
            // Event listeners
            floorFilter.addEventListener('change', applyFilters);
            areaFilter.addEventListener('change', applyFilters);
            searchFilter.addEventListener('input', applyFilters);
            itemSelect.addEventListener('change', updateStockWarning);
            
            locationCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const container = this.closest('.location-item').querySelector('.quantity-input-container');
                    if (this.checked) {
                        container.classList.remove('hidden');
                        const quantityInput = container.querySelector('input[type="number"]');
                        quantityInput.value = defaultQuantityInput.value;
                    } else {
                        container.classList.add('hidden');
                    }
                    updateSelectedCount();
                });
            });
            
            // Handle bulk selection
            selectAllButton.addEventListener('click', function() {
                const visibleItems = document.querySelectorAll('.location-item:not([style*="display: none"])');
                visibleItems.forEach(item => {
                    const checkbox = item.querySelector('.location-checkbox');
                    checkbox.checked = true;
                    const container = item.querySelector('.quantity-input-container');
                    container.classList.remove('hidden');
                    const quantityInput = container.querySelector('input[type="number"]');
                    quantityInput.value = defaultQuantityInput.value;
                });
                updateSelectedCount();
            });
            
            clearAllButton.addEventListener('click', function() {
                document.querySelectorAll('.location-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                    const container = checkbox.closest('.location-item').querySelector('.quantity-input-container');
                    container.classList.add('hidden');
                });
                updateSelectedCount();
            });
            
            // Initial calls
            applyFilters();
            toggleQuantityInputs();
            updateSelectedCount();
        });
    </script>
@endsection 