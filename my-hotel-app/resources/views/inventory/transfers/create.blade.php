@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Transfer Items from {{ $location->name }}
                </h2>
                <a href="{{ route('inventory.transfers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Back to Locations
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

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Transfer Items</h3>
                
                @if($location->items->isEmpty())
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    This location doesn't have any items to transfer.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('inventory.transfers.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Select Another Location
                        </a>
                    </div>
                @else
                    <form action="{{ route('inventory.transfers.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="source_location_id" value="{{ $location->id }}">
                        
                        <div class="grid grid-cols-1 gap-6 mt-4">
                            <!-- Source Location Info -->
                            <div class="bg-blue-50 p-4 rounded-md">
                                <h4 class="font-medium text-blue-800 mb-2">Source Location</h4>
                                <p class="text-blue-700">{{ $location->name }}</p>
                                <p class="text-sm text-blue-600">Floor {{ $location->floor_number }}, {{ ucfirst($location->area_type) }}
                                @if($location->room_number)
                                    , Room {{ $location->room_number }}
                                @endif
                                </p>
                            </div>
                            
                            <!-- Destination Location Selection -->
                            <div>
                                <label for="destination_location_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Destination Location *
                                </label>
                                <select name="destination_location_id" id="destination_location_id" required
                                       class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Select Destination Location</option>
                                    @foreach($destinations as $floor => $floorLocations)
                                        <optgroup label="Floor {{ $floor }}">
                                            @foreach($floorLocations->groupBy('area_type') as $areaType => $areaLocations)
                                                @foreach($areaLocations as $dest)
                                                    <option value="{{ $dest->id }}">
                                                        {{ $dest->name }}
                                                        @if($dest->room_number)
                                                            (Room {{ $dest->room_number }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Items Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Items to Transfer *
                                </label>
                                
                                <div class="border border-gray-300 rounded-md overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <input type="checkbox" id="select-all-items" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Item
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Available
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Quantity to Transfer
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($location->items as $item)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="checkbox" name="items[]" value="{{ $item->id }}" 
                                                               class="item-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                                        @if($item->sku)
                                                            <div class="text-sm text-gray-500">SKU: {{ $item->sku }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="text-sm text-gray-900">{{ $item->pivot->quantity }}</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="number" name="quantities[{{ $item->id }}]" 
                                                               class="quantity-input max-w-xs mt-1 block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                                               min="1" max="{{ $item->pivot->quantity }}" value="1" disabled>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                    Notes
                                </label>
                                <textarea id="notes" name="notes" rows="3" 
                                         class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md"
                                         placeholder="Optional notes about this transfer"></textarea>
                            </div>
                            
                            <!-- Submit -->
                            <div class="pt-4">
                                <button type="submit" 
                                       class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Transfer Items
                                </button>
                                <a href="{{ route('inventory.transfers.index') }}" 
                                   class="ml-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all-items');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const quantityInputs = document.querySelectorAll('.quantity-input');
        
        // Toggle quantity inputs based on item selection
        itemCheckboxes.forEach((checkbox, index) => {
            checkbox.addEventListener('change', function() {
                quantityInputs[index].disabled = !this.checked;
            });
        });
        
        // Select/deselect all items
        selectAllCheckbox?.addEventListener('change', function() {
            const isChecked = this.checked;
            
            itemCheckboxes.forEach((checkbox, index) => {
                checkbox.checked = isChecked;
                quantityInputs[index].disabled = !isChecked;
            });
        });
        
        // Form validation
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const selectedItems = document.querySelectorAll('.item-checkbox:checked').length;
            const destinationLocation = document.getElementById('destination_location_id').value;
            
            if (selectedItems === 0) {
                e.preventDefault();
                alert('Please select at least one item to transfer.');
                return false;
            }
            
            if (!destinationLocation) {
                e.preventDefault();
                alert('Please select a destination location.');
                return false;
            }
            
            return true;
        });
    });
</script>
@endsection 