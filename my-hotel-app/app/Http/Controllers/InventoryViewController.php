<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class InventoryViewController extends Controller
{
    // Display inventory stock view
    public function index(Request $request)
    {
        $status = $request->status;
        
        $items = Item::with(['category', 'inventory'])
            ->when($request->search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($status === 'active', function($query) {
                return $query->where('is_active', true);
            })
            ->when($status === 'inactive', function($query) {
                return $query->where('is_active', false);
            })
            ->orderBy('name')
            ->paginate(10)
            ->appends($request->only(['search', 'status']));
            
        $categories = Category::all();
        
        return view('inventory.stock_view', compact('items', 'categories', 'status'));
    }
    
    // Display items by category
    public function byCategory(Category $category, Request $request)
    {
        $status = $request->status;
        
        $items = Item::with('inventory')
            ->where('category_id', $category->id)
            ->when($request->search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($status === 'active', function($query) {
                return $query->where('is_active', true);
            })
            ->when($status === 'inactive', function($query) {
                return $query->where('is_active', false);
            })
            ->orderBy('name')
            ->paginate(10)
            ->appends($request->only(['search', 'status']));
            
        $categories = Category::all();
        
        return view('inventory.stock_view', compact('items', 'categories', 'category', 'status'));
    }
    
    // Process stock in from delivered purchase orders
    public function processStockIn()
    {
        $deliveredOrders = PurchaseOrder::where('status', 'delivered')
            ->with(['supplier', 'items'])
            ->get();
            
        foreach ($deliveredOrders as $order) {
            foreach ($order->items as $orderItem) {
                // Try to find an existing item with this name
                $item = Item::firstOrCreate(
                    ['name' => $orderItem->item_name],
                    [
                        'description' => 'Added from purchase order #' . $order->id,
                        'category_id' => 1 // Default category
                    ]
                );
                
                // Update or create inventory record
                $inventory = Inventory::firstOrNew(['item_id' => $item->id]);
                $inventory->current_stock += $orderItem->quantity;
                $inventory->last_stocked_at = now();
                $inventory->supplier_name = $order->supplier->name;
                $inventory->purchase_order_id = $order->id;
                $inventory->save();
            }
            
            // Mark the order as processed (optional - could add a flag to the purchase order)
        }
        
        return redirect()->route('inventory.view')
            ->with('success', 'Purchase orders have been processed into inventory.');
    }
} 