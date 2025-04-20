<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Location;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StockOutController extends Controller
{
    /**
     * Show the stock out form
     */
    public function index()
    {
        // Get all active items
        $items = Item::where('is_active', true)->orderBy('name')->get();
        
        // Get active locations grouped by floor and area type
        $locations = Location::where('is_active', true)
                           ->orderBy('floor_number')
                           ->orderBy('area_type')
                           ->orderByRaw('CAST(room_number AS UNSIGNED)')
                           ->get()
                           ->groupBy('floor_number');
                           
        // Get unique area types for filtering
        $areaTypes = Location::distinct('area_type')->pluck('area_type');
        
        return view('inventory.stock.out', compact('items', 'locations', 'areaTypes'));
    }
    
    /**
     * Process a stock out request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'locations' => 'required|array',
            'locations.*' => 'exists:locations,id',
            'location_quantities' => 'required|array',
            'location_quantities.*' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $item = Item::findOrFail($validated['item_id']);
        $totalCurrentStock = $item->getCurrentStock();
        
        // Calculate total quantity needed across all locations
        $totalQuantityNeeded = 0;
        foreach ($validated['locations'] as $locationId) {
            if (isset($validated['location_quantities'][$locationId])) {
                $totalQuantityNeeded += $validated['location_quantities'][$locationId];
            }
        }
        
        // Check if there's enough stock
        if ($totalCurrentStock < $totalQuantityNeeded) {
            return back()->withInput()->withErrors([
                'quantity' => "Not enough stock available. Current stock: {$totalCurrentStock}, Required: {$totalQuantityNeeded}"
            ]);
        }
        
        // Start a database transaction
        DB::beginTransaction();
        
        try {
            foreach ($validated['locations'] as $locationId) {
                $location = Location::findOrFail($locationId);
                
                // Skip if no quantity specified for this location
                if (!isset($validated['location_quantities'][$locationId])) {
                    continue;
                }
                
                $quantity = $validated['location_quantities'][$locationId];
                
                // Create stock movement record
                StockMovement::create([
                    'item_id' => $item->id,
                    'to_location_id' => $location->id,
                    'quantity' => $quantity,
                    'type' => 'out', // Stock going out from central inventory to a location
                    'user_id' => Auth::id(),
                    'notes' => $validated['notes'],
                ]);
                
                // Update or create location_items pivot
                $location->items()->syncWithoutDetaching([
                    $item->id => [
                        'quantity' => DB::raw('quantity + ' . $quantity),
                    ]
                ]);
                
                // Reduce from inventory
                if ($item->inventory) {
                    $item->inventory->update([
                        'current_stock' => DB::raw('current_stock - ' . $quantity)
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('inventory.stock.out')
                          ->with('success', "Successfully assigned {$item->name} to " . 
                                 (count($validated['locations']) > 1 ? count($validated['locations']) . " locations" : "location"));
                                 
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Show the bulk stock out form
     */
    public function bulkIndex()
    {
        // Get all active items
        $items = Item::where('is_active', true)->orderBy('name')->get();
        
        // Get floors for filtering
        $floors = Location::distinct('floor_number')
                        ->orderBy('floor_number')
                        ->pluck('floor_number');
                        
        // Get area types for filtering
        $areaTypes = Location::distinct('area_type')
                            ->pluck('area_type')
                            ->map(function($type) {
                                return ['value' => $type, 'label' => ucfirst($type)];
                            });
        
        return view('inventory.stock.bulk-out', compact('items', 'floors', 'areaTypes'));
    }
    
    /**
     * Process a bulk stock out request
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*' => 'exists:items,id',
            'floor_number' => 'required|integer|min:0',
            'area_type' => 'required|string',
            'room_start' => 'nullable|integer|min:0',
            'room_end' => 'nullable|integer|min:0',
            'use_room_range' => 'required|boolean',
            'location_quantities' => 'required|array',
            'location_quantities.*' => 'array',
            'location_quantities.*.*' => 'integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Find all matching locations
        $locationsQuery = Location::where('floor_number', $validated['floor_number'])
                                ->where('area_type', $validated['area_type'])
                                ->where('is_active', true);
        
        // Apply room number filtering if needed
        if ($validated['use_room_range'] && $validated['room_start'] !== null && $validated['room_end'] !== null) {
            // Convert to integers for proper comparison
            $minRoom = intval(min($validated['room_start'], $validated['room_end']));
            $maxRoom = intval(max($validated['room_start'], $validated['room_end']));
            
            // Use whereRaw for proper numeric comparison
            $locationsQuery->whereRaw('CAST(room_number AS UNSIGNED) >= ?', [$minRoom])
                          ->whereRaw('CAST(room_number AS UNSIGNED) <= ?', [$maxRoom]);
        } elseif (!$validated['use_room_range']) {
            // If not using room range, get locations without room numbers
            $locationsQuery->whereNull('room_number');
        }
        
        $locations = $locationsQuery->get();
        
        if ($locations->isEmpty()) {
            return back()->withInput()->withErrors([
                'general' => 'No matching active locations found for the specified criteria.'
            ]);
        }
        
        // Check stock for all items
        $items = Item::findMany($validated['items']);
        $itemsWithInsufficientStock = [];
        
        // Calculate total required stock per item
        $requiredStockPerItem = [];
        foreach ($items as $item) {
            $requiredStockPerItem[$item->id] = 0;
        }
        
        // Sum up quantities for each item across all locations
        foreach ($locations as $location) {
            if (isset($validated['location_quantities'][$location->id])) {
                foreach ($validated['location_quantities'][$location->id] as $itemId => $quantity) {
                    if (isset($requiredStockPerItem[$itemId])) {
                        $requiredStockPerItem[$itemId] += $quantity;
                    }
                }
            }
        }
        
        // Check if any item has insufficient stock
        foreach ($items as $item) {
            $requiredStock = $requiredStockPerItem[$item->id] ?? 0;
            $availableStock = $item->getCurrentStock();
            
            if ($availableStock < $requiredStock) {
                $itemsWithInsufficientStock[] = [
                    'name' => $item->name,
                    'available' => $availableStock,
                    'required' => $requiredStock
                ];
            }
        }
        
        if (!empty($itemsWithInsufficientStock)) {
            $errorMessage = "Insufficient stock for the following items:<br>";
            foreach ($itemsWithInsufficientStock as $item) {
                $errorMessage .= "- {$item['name']}: Available {$item['available']}, Required {$item['required']}<br>";
            }
            
            return back()->withInput()->withErrors([
                'general' => $errorMessage
            ]);
        }
        
        // Start a database transaction
        DB::beginTransaction();
        
        try {
            foreach ($locations as $location) {
                // Skip if no quantities specified for this location
                if (!isset($validated['location_quantities'][$location->id])) {
                    continue;
                }
                
                $locationQuantities = $validated['location_quantities'][$location->id];
                
                foreach ($items as $item) {
                    // Skip if no quantity specified for this item at this location
                    if (!isset($locationQuantities[$item->id]) || intval($locationQuantities[$item->id]) <= 0) {
                        continue;
                    }
                    
                    $quantity = intval($locationQuantities[$item->id]);
                    
                    // Create stock movement record
                    StockMovement::create([
                        'item_id' => $item->id,
                        'to_location_id' => $location->id,
                        'quantity' => $quantity,
                        'type' => 'out',
                        'user_id' => Auth::id(),
                        'notes' => $validated['notes'],
                    ]);
                    
                    // Update or create location_items pivot
                    $location->items()->syncWithoutDetaching([
                        $item->id => [
                            'quantity' => DB::raw('quantity + ' . $quantity),
                        ]
                    ]);
                    
                    // Reduce from inventory
                    if ($item->inventory) {
                        $item->inventory->update([
                            'current_stock' => DB::raw('current_stock - ' . $quantity)
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            $totalItems = count($items);
            $totalLocations = $locations->count();
            
            return redirect()->route('inventory.stock.bulk-out')
                          ->with('success', "Successfully assigned items to locations. Processed {$totalItems} items across {$totalLocations} locations.");
                            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
} 