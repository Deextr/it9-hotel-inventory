@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Create Multiple Locations
                </h2>
                <a href="{{ route('locations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Oops!</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800">
                                    This form allows you to create multiple locations in a range. For example, all rooms from 101 to 120 on floor 1.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('locations.store-batch') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1">
                                <div class="mb-4">
                                    <label for="floor_number" class="block text-sm font-medium text-gray-700 mb-2">Floor Number *</label>
                                    <input type="number" name="floor_number" id="floor_number" value="{{ old('floor_number') }}" min="0" 
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required>
                                    <p class="text-xs text-gray-500 mt-1">Enter the floor number (e.g., 1, 2, 3)</p>
                                </div>

                                <div class="mb-4">
                                    <label for="area_type" class="block text-sm font-medium text-gray-700 mb-2">Area Type *</label>
                                    <select name="area_type" id="area_type" 
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required>
                                        <option value="">Select an area type</option>
                                        @foreach ($areaTypes as $value => $label)
                                            <option value="{{ $value }}" {{ old('area_type') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Select the type of area</p>
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea name="description" id="description" rows="3" 
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Optional shared description for all created locations</p>
                                </div>
                            </div>

                            <div class="col-span-1">
                                <div class="mb-4">
                                    <label for="prefix" class="block text-sm font-medium text-gray-700 mb-2">Room Number Prefix</label>
                                    <input type="text" name="prefix" id="prefix" value="{{ old('prefix') }}" 
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <p class="text-xs text-gray-500 mt-1">Optional prefix for room numbers (e.g., "RM" for RM101)</p>
                                </div>

                                <div class="mb-4">
                                    <label for="start_number" class="block text-sm font-medium text-gray-700 mb-2">Start Number *</label>
                                    <input type="number" name="start_number" id="start_number" value="{{ old('start_number') }}" min="1" 
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required>
                                    <p class="text-xs text-gray-500 mt-1">First room number in the range (e.g., 101)</p>
                                </div>

                                <div class="mb-4">
                                    <label for="end_number" class="block text-sm font-medium text-gray-700 mb-2">End Number *</label>
                                    <input type="number" name="end_number" id="end_number" value="{{ old('end_number') }}" min="1" 
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required>
                                    <p class="text-xs text-gray-500 mt-1">Last room number in the range (e.g., 120)</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h3 class="text-md font-medium text-gray-900 mb-2">Preview:</h3>
                            <p class="text-sm text-gray-700 mb-1" id="preview-text">Please fill out the form to see a preview of locations that will be created.</p>
                            <div class="mt-2 text-sm text-gray-600" id="preview-list"></div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('locations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Create Locations
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const floorNumberInput = document.getElementById('floor_number');
            const areaTypeSelect = document.getElementById('area_type');
            const prefixInput = document.getElementById('prefix');
            const startNumberInput = document.getElementById('start_number');
            const endNumberInput = document.getElementById('end_number');
            const previewText = document.getElementById('preview-text');
            const previewList = document.getElementById('preview-list');

            function updatePreview() {
                const floorNumber = floorNumberInput.value;
                const areaTypeIndex = areaTypeSelect.selectedIndex;
                const areaType = areaTypeIndex > 0 ? areaTypeSelect.options[areaTypeIndex].text : '';
                const prefix = prefixInput.value || '';
                const startNumber = parseInt(startNumberInput.value);
                const endNumber = parseInt(endNumberInput.value);

                // Clear previous preview
                previewList.innerHTML = '';
                
                if (floorNumber && areaType && !isNaN(startNumber) && !isNaN(endNumber) && endNumber >= startNumber) {
                    previewText.textContent = `Creating ${endNumber - startNumber + 1} locations on Floor ${floorNumber}:`;
                    
                    // Show a sample of locations (up to 5)
                    const total = endNumber - startNumber + 1;
                    const samplesToShow = Math.min(5, total);
                    let skipInterval = 1;
                    
                    if (total > 5) {
                        skipInterval = Math.floor(total / 5);
                    }
                    
                    let shown = 0;
                    let lastShown = -1;
                    
                    for (let i = startNumber; i <= endNumber && shown < samplesToShow; i += skipInterval) {
                        const roomNumber = prefix + i;
                        const name = `Floor ${floorNumber} - ${areaType} ${roomNumber}`;
                        
                        const item = document.createElement('div');
                        item.textContent = name;
                        item.className = 'mb-1';
                        previewList.appendChild(item);
                        
                        shown++;
                        lastShown = i;
                    }
                    
                    // Always show the last item if we're skipping
                    if (lastShown !== endNumber && total > 5) {
                        const roomNumber = prefix + endNumber;
                        const name = `Floor ${floorNumber} - ${areaType} ${roomNumber}`;
                        
                        if (lastShown !== startNumber) {
                            const ellipsis = document.createElement('div');
                            ellipsis.textContent = '...';
                            ellipsis.className = 'mb-1 ml-2 text-gray-500';
                            previewList.appendChild(ellipsis);
                        }
                        
                        const item = document.createElement('div');
                        item.textContent = name;
                        item.className = 'mb-1';
                        previewList.appendChild(item);
                    }
                } else {
                    previewText.textContent = 'Please fill out the form to see a preview of locations that will be created.';
                }
            }

            // Update preview when any input changes
            floorNumberInput.addEventListener('input', updatePreview);
            areaTypeSelect.addEventListener('change', updatePreview);
            prefixInput.addEventListener('input', updatePreview);
            startNumberInput.addEventListener('input', updatePreview);
            endNumberInput.addEventListener('input', updatePreview);
        });
    </script>
@endsection 