<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Location;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ItemTransferController extends Controller
{
    /**
     * Show the transfer form
     */
    public function index()
    {
        // Get all active locations
        $locations = Location::where('is_active', true)
                          ->orderBy('floor_number')
                          ->orderBy('area_type')
                          ->orderByRaw('CAST(room_number AS UNSIGNED)')
                          ->get()
                          ->groupBy('floor_number');
        
        // Get unique area types for filtering
        $areaTypes = Location::distinct('area_type')->pluck('area_type');
        
        return view('inventory.transfers.index', compact('locations', 'areaTypes'));
    }
    
    /**
     * Show transfer form for a specific location
     */
    public function create(Location $location)
    {
        // Get items currently in this location
        $location->load('items');
        
        // Get all active locations for destination dropdown (excluding current location)
        $destinations = Location::where('is_active', true)
                             ->where('id', '!=', $location->id)
                             ->orderBy('floor_number')
                             ->orderBy('area_type')
                             ->orderByRaw('CAST(room_number AS UNSIGNED)')
                             ->get()
                             ->groupBy('floor_number');
        
        return view('inventory.transfers.create', compact('location', 'destinations'));
    }
    
    /**
     * Process a transfer request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'source_location_id' => 'required|exists:locations,id',
            'destination_location_id' => 'required|exists:locations,id|different:source_location_id',
            'items' => 'required|array',
            'items.*' => 'exists:items,id',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Get source and destination locations
        $sourceLocation = Location::findOrFail($validated['source_location_id']);
        $destinationLocation = Location::findOrFail($validated['destination_location_id']);
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            $transferredItems = [];
            
            foreach ($validated['items'] as $itemId) {
                // Get current quantity in source location
                $pivotData = $sourceLocation->items()->where('items.id', $itemId)->first()->pivot ?? null;
                
                if (!$pivotData) {
                    // Item not in source location
                    continue;
                }
                
                $quantity = $validated['quantities'][$itemId] ?? 0;
                
                // Check if there's enough quantity to transfer
                if ($quantity <= 0 || $pivotData->quantity < $quantity) {
                    continue;
                }
                
                // Update source location (reduce quantity)
                if ($pivotData->quantity == $quantity) {
                    // Remove the item entirely if all quantity is transferred
                    $sourceLocation->items()->detach($itemId);
                } else {
                    // Reduce the quantity
                    $sourceLocation->items()->updateExistingPivot($itemId, [
                        'quantity' => DB::raw("quantity - {$quantity}")
                    ]);
                }
                
                // Update destination location (add quantity)
                $destinationLocation->items()->syncWithoutDetaching([
                    $itemId => [
                        'quantity' => DB::raw("COALESCE(quantity, 0) + {$quantity}")
                    ]
                ]);
                
                // Create stock movement record
                StockMovement::create([
                    'item_id' => $itemId,
                    'from_location_id' => $sourceLocation->id,
                    'to_location_id' => $destinationLocation->id,
                    'quantity' => $quantity,
                    'type' => 'transfer',
                    'user_id' => Auth::id(),
                    'notes' => $validated['notes'],
                ]);
                
                $item = Item::find($itemId);
                $transferredItems[] = [
                    'name' => $item->name,
                    'quantity' => $quantity
                ];
            }
            
            if (empty($transferredItems)) {
                return back()->withInput()->withErrors([
                    'general' => 'No items were transferred. Please check quantities and try again.'
                ]);
            }
            
            DB::commit();
            
            // Format success message
            $itemsText = count($transferredItems) > 1
                ? count($transferredItems) . " items"
                : "'" . $transferredItems[0]['name'] . "'";
            
            return redirect()->route('locations.show', $sourceLocation)
                          ->with('success', "Successfully transferred {$itemsText} to {$destinationLocation->name}");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors([
                'general' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get items for a location via API
     */
    public function getLocationItems(Location $location)
    {
        $location->load(['items' => function ($query) {
            $query->select('items.id', 'items.name', 'items.sku')
                  ->orderBy('items.name');
        }]);
        
        $items = $location->items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'sku' => $item->sku,
                'quantity' => $item->pivot->quantity
            ];
        });
        
        return response()->json(['items' => $items]);
    }
} 