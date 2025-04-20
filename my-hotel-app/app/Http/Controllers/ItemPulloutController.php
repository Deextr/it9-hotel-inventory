<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemPullout;
use App\Models\Location;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemPulloutController extends Controller
{
    /**
     * Display a listing of pullouts.
     */
    public function index()
    {
        $pullouts = ItemPullout::with(['item', 'location', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('inventory.pullouts.index', compact('pullouts'));
    }

    /**
     * Show the form for creating a new pullout.
     */
    public function create()
    {
        $locations = Location::where('is_active', true)
            ->orderBy('floor_number')
            ->orderBy('area_type')
            ->orderByRaw('CAST(room_number AS UNSIGNED)')
            ->get()
            ->groupBy('floor_number');
            
        return view('inventory.pullouts.create', compact('locations'));
    }

    /**
     * Get items for a specific location (AJAX).
     */
    public function getLocationItems(Location $location)
    {
        try {
            // Log location details to help debug
            Log::info('Getting items for location', [
                'location_id' => $location->id,
                'location_name' => $location->name,
                'floor' => $location->floor_number,
                'area' => $location->area_type,
                'room' => $location->room_number
            ]);
            
            // Get items with their relationships
            $items = $location->items()->with('category')->get();
            
            Log::info('Raw items count', ['count' => $items->count()]);
            
            // Return only locations that have items with quantity > 0
            $filteredItems = $items->filter(function($item) {
                return $item->pivot->quantity > 0;
            })->values();
            
            Log::info('Filtered items count', ['count' => $filteredItems->count()]);
            
            return response()->json($filteredItems);
        } catch (\Exception $e) {
            Log::error('Error getting location items', [
                'location_id' => $location->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to retrieve items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created pullout in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            // Log before checking
            Log::info('Pullout request received', [
                'location_id' => $request->location_id,
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
                'reason' => $request->reason
            ]);
            
            // Check if item exists in location with sufficient quantity
            $locationItem = DB::table('location_items')
                ->where('location_id', $request->location_id)
                ->where('item_id', $request->item_id)
                ->first();
                
            if (!$locationItem) {
                DB::rollBack();
                Log::warning('Pullout failed: Item not found in location', [
                    'location_id' => $request->location_id,
                    'item_id' => $request->item_id
                ]);
                return back()->with('error', 'The selected item is not available in this location.')
                    ->withInput();
            }
            
            if ($locationItem->quantity < $request->quantity) {
                DB::rollBack();
                Log::warning('Pullout failed: Insufficient quantity', [
                    'location_id' => $request->location_id,
                    'item_id' => $request->item_id,
                    'requested' => $request->quantity,
                    'available' => $locationItem->quantity
                ]);
                return back()->with('error', "Insufficient quantity. Only {$locationItem->quantity} available.")
                    ->withInput();
            }
            
            // Create pullout record
            $pullout = ItemPullout::create([
                'item_id' => $request->item_id,
                'location_id' => $request->location_id,
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'status' => 'completed', // Auto-complete for now
                'user_id' => Auth::id(),
                'notes' => $request->notes,
            ]);
            
            Log::info('Pullout record created', ['pullout_id' => $pullout->id]);
            
            // Update location_items quantity
            DB::table('location_items')
                ->where('location_id', $request->location_id)
                ->where('item_id', $request->item_id)
                ->decrement('quantity', $request->quantity);
                
            // Delete the record if quantity becomes 0
            DB::table('location_items')
                ->where('location_id', $request->location_id)
                ->where('item_id', $request->item_id)
                ->where('quantity', 0)
                ->delete();
                
            // Create a stock movement record
            StockMovement::create([
                'item_id' => $request->item_id,
                'from_location_id' => $request->location_id,
                'to_location_id' => null,
                'quantity' => $request->quantity,
                'type' => 'out',
                'user_id' => Auth::id(),
                'notes' => 'Pullout: ' . $request->reason,
            ]);
            
            DB::commit();
            Log::info('Pullout completed successfully', ['pullout_id' => $pullout->id]);
            
            return redirect()->route('inventory.pullouts.index')
                ->with('success', 'Item pulled out successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Pullout error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }
} 