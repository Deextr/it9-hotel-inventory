@extends('layouts.app')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Transfer Items Between Locations
            </h2>
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
                <h3 class="text-lg font-medium text-gray-900 mb-4">Select Source Location</h3>
                
                <p class="mb-4 text-gray-600">Select a location to transfer items from:</p>
                
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($locations as $floor => $floorLocations)
                        <div class="bg-gray-50 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-lg mb-2">Floor {{ $floor }}</h4>
                            <div class="space-y-2">
                                @foreach($floorLocations->groupBy('area_type') as $areaType => $areaLocations)
                                    <div>
                                        <h5 class="font-medium text-gray-700 mb-1">{{ ucfirst($areaType) }}</h5>
                                        <ul class="pl-4 space-y-1">
                                            @foreach($areaLocations as $location)
                                                <li>
                                                    <a href="{{ route('inventory.transfers.create', $location) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900">
                                                        {{ $location->name }}
                                                        @if($location->room_number)
                                                            (Room {{ $location->room_number }})
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($locations->isEmpty())
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    No active locations found. Please create locations first.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 