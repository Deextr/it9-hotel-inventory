<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LocationController extends Controller
{
    /**
     * Display a listing of the locations.
     */
    public function index(Request $request)
    {
        $query = Location::query();
        
        // Filter by floor number
        if ($request->filled('floor')) {
            $query->where('floor_number', $request->floor);
        }
        
        // Filter by area type
        if ($request->filled('area_type')) {
            $query->where('area_type', $request->area_type);
        }
        
        // Filter by active status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }
        
        // Search by name, room number or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('room_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Order results
        $locations = $query->orderBy('floor_number')
                           ->orderBy('area_type')
                           ->orderBy('room_number')
                           ->paginate(15)
                           ->withQueryString();
        
        // Get area types for dropdown
        $areaTypes = $this->getAreaTypes();
        
        // Get unique floor numbers for the filter dropdown
        $floors = Location::distinct()->orderBy('floor_number')->pluck('floor_number');

        return view('locations.index', compact('locations', 'areaTypes', 'floors'));
    }

    /**
     * Show the form for creating a new location.
     */
    public function create()
    {
        $areaTypes = $this->getAreaTypes();
        return view('locations.create', compact('areaTypes'));
    }

    /**
     * Show form for batch creating multiple locations at once.
     */
    public function createBatch()
    {
        $areaTypes = $this->getAreaTypes();
        return view('locations.create-batch', compact('areaTypes'));
    }

    /**
     * Store a newly created location in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'floor_number' => 'required|integer|min:0',
            'area_type' => ['required', Rule::in(array_keys($this->getAreaTypes()))],
            'room_number' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
        ]);

        // Check for duplicate floor/area/room combination
        $exists = Location::where('floor_number', $validated['floor_number'])
                         ->where('area_type', $validated['area_type'])
                         ->where('room_number', $validated['room_number'])
                         ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'room_number' => 'A location with this floor, area type and room number already exists.'
            ]);
        }

        Location::create($validated);

        return redirect()->route('locations.index')
                        ->with('success', 'Location created successfully.');
    }

    /**
     * Store multiple locations in storage.
     */
    public function storeBatch(Request $request)
    {
        $request->validate([
            'floor_number' => 'required|integer|min:0',
            'area_type' => ['required', Rule::in(array_keys($this->getAreaTypes()))],
            'start_number' => 'required|integer|min:1',
            'end_number' => 'required|integer|min:1|gte:start_number',
            'prefix' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:1000',
        ]);

        $count = 0;
        $errors = [];

        for ($i = $request->start_number; $i <= $request->end_number; $i++) {
            $roomNumber = $request->prefix . $i;
            
            // Generate name based on floor, area and room number
            $name = "Floor {$request->floor_number} " . ucfirst($request->area_type) . " {$roomNumber}";
            
            // Check for duplicate
            $exists = Location::where('floor_number', $request->floor_number)
                             ->where('area_type', $request->area_type)
                             ->where('room_number', $roomNumber)
                             ->exists();

            if (!$exists) {
                Location::create([
                    'name' => $name,
                    'floor_number' => $request->floor_number,
                    'area_type' => $request->area_type,
                    'room_number' => $roomNumber,
                    'description' => $request->description,
                ]);
                $count++;
            } else {
                $errors[] = "Location with Room {$roomNumber} already exists.";
            }
        }

        if ($count > 0) {
            $message = "{$count} locations created successfully.";
            if (!empty($errors)) {
                $message .= " Some locations were skipped as they already exist.";
            }
            return redirect()->route('locations.index')->with('success', $message);
        }

        return back()->withInput()->withErrors([
            'general' => 'No locations were created. All specified locations already exist.'
        ]);
    }

    /**
     * Show the form for editing the specified location.
     */
    public function edit(Location $location)
    {
        $areaTypes = $this->getAreaTypes();
        return view('locations.edit', compact('location', 'areaTypes'));
    }

    /**
     * Update the specified location in storage.
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'floor_number' => 'required|integer|min:0',
            'area_type' => ['required', Rule::in(array_keys($this->getAreaTypes()))],
            'room_number' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        // Check for duplicate floor/area/room combination (excluding current location)
        $exists = Location::where('floor_number', $validated['floor_number'])
                         ->where('area_type', $validated['area_type'])
                         ->where('room_number', $validated['room_number'])
                         ->where('id', '!=', $location->id)
                         ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'room_number' => 'A location with this floor, area type and room number already exists.'
            ]);
        }

        // Set is_active to false if not present in request
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        $location->update($validated);

        return redirect()->route('locations.index')
                         ->with('success', 'Location updated successfully.');
    }

    /**
     * Remove the specified location from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.index')
                         ->with('success', 'Location deleted successfully.');
    }

    /**
     * Get area types for dropdown selects
     */
    private function getAreaTypes()
    {
        return [
            'room' => 'Room',
            'kitchen' => 'Kitchen',
            'hallway' => 'Hallway',
            'reception' => 'Reception', 
            'restaurant' => 'Restaurant',
            'office' => 'Office',
            'storage' => 'Storage',
            'other' => 'Other',
        ];
    }
} 