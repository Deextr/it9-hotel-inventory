@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Pull Out Items
                </h2>
                <a href="{{ route('inventory.pullouts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Back to Pullouts
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Create Item Pullout</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Use this form to record items that need to be removed from their locations due to damage or other issues.
                </p>
                
                <!-- Debug Info (Only shown during troubleshooting) -->
                <!-- Removed debug panel as issue is fixed -->
                
                <form action="{{ route('inventory.pullouts.store') }}" method="POST" id="pullout-form">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Location Selection -->
                        <div class="md:col-span-1">
                            <label for="location_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Source Location *
                            </label>
                            <select name="location_id" id="location_id" required
                                   class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select Location</option>
                                @foreach($locations as $floor => $floorLocations)
                                    <optgroup label="Floor {{ $floor }}">
                                        @foreach($floorLocations->groupBy('area_type') as $areaType => $areaLocations)
                                            @foreach($areaLocations as $location)
                                                <option value="{{ $location->id }}">
                                                    {{ $location->name }}
                                                    @if($location->room_number)
                                                        (Room {{ $location->room_number }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Item Selection (will be populated via JS) -->
                        <div class="md:col-span-2">
                            <label for="item_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Item to Pull Out *
                            </label>
                            <select name="item_id" id="item_id" required disabled
                                   class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select a location first</option>
                            </select>
                            <p id="no-items-message" class="hidden mt-2 text-sm text-yellow-600">
                                No items available in this location.
                            </p>
                        </div>
                        
                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                                Quantity to Pull Out *
                            </label>
                            <input type="number" name="quantity" id="quantity" required min="1" value="1" disabled
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p id="max-quantity" class="mt-1 text-sm text-gray-500 hidden">
                                Available: <span id="available-quantity">0</span>
                            </p>
                        </div>
                        
                        <!-- Reason for Pullout -->
                        <div class="md:col-span-2">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                                Reason for Pullout *
                            </label>
                            <select name="reason" id="reason" required
                                   class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select a Reason</option>
                                <option value="Damaged">Damaged</option>
                                <option value="Defective">Defective</option>
                                <option value="Expired">Expired</option>
                                <option value="Incorrect Item">Incorrect Item</option>
                                <option value="Maintenance Required">Maintenance Required</option>
                                <option value="No Longer Needed">No Longer Needed</option>
                                <option value="Other">Other (Specify in Notes)</option>
                            </select>
                        </div>
                        
                        <!-- Notes -->
                        <div class="md:col-span-3">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Additional Notes
                            </label>
                            <textarea id="notes" name="notes" rows="3" 
                                     class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md"
                                     placeholder="Enter additional details about this pullout"></textarea>
                        </div>
                        
                        <!-- Submit -->
                        <div class="md:col-span-3 pt-4">
                            <button type="submit" 
                                   class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Submit Pullout
                            </button>
                            <a href="{{ route('inventory.pullouts.index') }}" 
                               class="ml-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const locationSelect = document.getElementById('location_id');
        const itemSelect = document.getElementById('item_id');
        const quantityInput = document.getElementById('quantity');
        const noItemsMessage = document.getElementById('no-items-message');
        const maxQuantityMessage = document.getElementById('max-quantity');
        const availableQuantitySpan = document.getElementById('available-quantity');
        
        // Form validation
        document.getElementById('pullout-form').addEventListener('submit', function(e) {
            if (!locationSelect.value) {
                e.preventDefault();
                alert('Please select a location.');
                return false;
            }
            
            if (!itemSelect.value) {
                e.preventDefault();
                alert('Please select an item.');
                return false;
            }
            
            if (!quantityInput.value || quantityInput.value < 1) {
                e.preventDefault();
                alert('Please enter a valid quantity (minimum 1).');
                return false;
            }
            
            const maxQty = parseInt(quantityInput.max, 10);
            const enteredQty = parseInt(quantityInput.value, 10);
            
            if (enteredQty > maxQty) {
                e.preventDefault();
                alert(`The maximum quantity available is ${maxQty}.`);
                return false;
            }
            
            return true;
        });
        
        // Handle location change
        locationSelect.addEventListener('change', function() {
            const locationId = this.value;
            itemSelect.disabled = true;
            quantityInput.disabled = true;
            noItemsMessage.classList.add('hidden');
            
            if (!locationId) {
                resetItemSelect();
                return;
            }
            
            // Fetch items for the selected location
            fetch(`/api/locations/${locationId}/available-items`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Network response was not ok: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    resetItemSelect();
                    
                    // Handle both array format and data property format
                    const items = Array.isArray(data) ? data : (data.data || []);
                    
                    if (items.length === 0) {
                        noItemsMessage.classList.remove('hidden');
                        return;
                    }
                    
                    // Add items to select
                    items.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = `${item.name}${item.category ? ` (${item.category.name})` : ''}`;
                        option.dataset.quantity = item.pivot.quantity;
                        itemSelect.appendChild(option);
                    });
                    
                    itemSelect.disabled = false;
                    
                    // Trigger change to set initial quantity
                    if (itemSelect.options.length > 1) {
                        itemSelect.selectedIndex = 1;
                        itemSelect.dispatchEvent(new Event('change'));
                    }
                })
                .catch(error => {
                    console.error('Error fetching items:', error);
                    noItemsMessage.classList.remove('hidden');
                });
        });
        
        // Handle item change
        itemSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (!selectedOption || !selectedOption.value) {
                quantityInput.disabled = true;
                maxQuantityMessage.classList.add('hidden');
                return;
            }
            
            const maxQuantity = parseInt(selectedOption.dataset.quantity, 10);
            availableQuantitySpan.textContent = maxQuantity;
            quantityInput.max = maxQuantity;
            quantityInput.value = Math.min(1, maxQuantity);
            quantityInput.disabled = false;
            maxQuantityMessage.classList.remove('hidden');
        });
        
        // Reset item select
        function resetItemSelect() {
            itemSelect.innerHTML = '<option value="">Select an item</option>';
            itemSelect.disabled = true;
            quantityInput.disabled = true;
            maxQuantityMessage.classList.add('hidden');
        }
    });
</script>
@endsection 