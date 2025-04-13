@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Create New Location
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

                    <form action="{{ route('locations.store') }}" method="POST">
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
                                    <label for="room_number" class="block text-sm font-medium text-gray-700 mb-2">Room Number</label>
                                    <input type="text" name="room_number" id="room_number" value="{{ old('room_number') }}" 
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <p class="text-xs text-gray-500 mt-1">Enter the room number (e.g., 101, 102) if applicable</p>
                                </div>
                            </div>

                            <div class="col-span-1">
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Location Name *</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required>
                                    <p class="text-xs text-gray-500 mt-1">Enter a descriptive name for this location</p>
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea name="description" id="description" rows="5" 
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Optional additional details about this location</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('locations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Create Location
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
            const roomNumberInput = document.getElementById('room_number');
            const nameInput = document.getElementById('name');

            function updateName() {
                const floorNumber = floorNumberInput.value;
                const areaType = areaTypeSelect.options[areaTypeSelect.selectedIndex]?.text || '';
                const roomNumber = roomNumberInput.value;

                if (floorNumber && areaType) {
                    let name = `Floor ${floorNumber} - ${areaType}`;
                    if (roomNumber) {
                        name += ` ${roomNumber}`;
                    }
                    nameInput.value = name;
                }
            }

            floorNumberInput.addEventListener('input', updateName);
            areaTypeSelect.addEventListener('change', updateName);
            roomNumberInput.addEventListener('input', updateName);
        });
    </script>
@endsection 