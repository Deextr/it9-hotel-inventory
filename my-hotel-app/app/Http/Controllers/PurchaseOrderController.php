<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier');
        
        // Filter by status
        if ($request->has('status')) {
            $status = $request->input('status');
            if (in_array($status, ['pending', 'delivered', 'canceled'])) {
                $query->where('status', $status);
            }
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Sort results
        $sortField = $request->input('sort', 'order_date');
        $sortDirection = $request->input('direction', 'desc');
        
        if (in_array($sortField, ['id', 'order_date', 'total_amount', 'status'])) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('order_date');
        }
        
        $purchaseOrders = $query->paginate(10)->withQueryString();
        
        return view('inventory.purchase_orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();
        return view('inventory.purchase_orders.create', compact('suppliers', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total amount
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }

            // Create purchase order
            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $validated['supplier_id'],
                'order_date' => $validated['order_date'],
                'status' => 'pending',
                'total_amount' => $totalAmount,
            ]);

            // Create purchase order items
            foreach ($validated['items'] as $itemData) {
                $item = Item::findOrFail($itemData['item_id']);
                $subtotal = $itemData['quantity'] * $itemData['unit_price'];
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'item_name' => $item->name,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            DB::commit();

            return redirect()->route('inventory.purchase_orders.index')
                ->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create purchase order: ' . $e->getMessage())->withInput();
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('supplier', 'items');
        return view('inventory.purchase_orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return redirect()->route('inventory.purchase_orders.show', $purchaseOrder)
                ->with('error', 'Cannot edit a purchase order that has been delivered or canceled.');
        }
        
        $suppliers = Supplier::all();
        $items = Item::orderBy('name')->get();
        $purchaseOrder->load('items');
        
        // Map existing items to include their item_id based on name matching
        foreach ($purchaseOrder->items as $orderItem) {
            $item = Item::where('name', $orderItem->item_name)->first();
            $orderItem->item_id = $item ? $item->id : null;
        }
        
        return view('inventory.purchase_orders.edit', compact('purchaseOrder', 'suppliers', 'items'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return redirect()->route('inventory.purchase_orders.show', $purchaseOrder)
                ->with('error', 'Cannot update a purchase order that has been delivered or canceled.');
        }
        
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:purchase_order_items,id',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total amount
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }

            // Update purchase order
            $purchaseOrder->update([
                'supplier_id' => $validated['supplier_id'],
                'total_amount' => $totalAmount,
            ]);

            // Delete existing items
            $purchaseOrder->items()->delete();

            // Create new purchase order items
            foreach ($validated['items'] as $itemData) {
                $item = Item::findOrFail($itemData['item_id']);
                $subtotal = $itemData['quantity'] * $itemData['unit_price'];
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'item_name' => $item->name,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            DB::commit();

            return redirect()->route('inventory.purchase_orders.index')
                ->with('success', 'Purchase order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update purchase order: ' . $e->getMessage())->withInput();
        }
    }

    public function markAsDelivered(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return back()->with('error', 'This purchase order has already been ' . $purchaseOrder->status . '.');
        }

        try {
            DB::beginTransaction();
            
            // Store old status for audit log
            $oldStatus = $purchaseOrder->status;
            
            $purchaseOrder->update([
                'status' => 'delivered',
                'delivered_date' => now(),
            ]);
            
            // Create custom audit log for status change
            AuditService::createLog(
                'status_changed',
                'purchase_orders',
                $purchaseOrder->id,
                ['status' => $oldStatus],
                ['status' => 'delivered', 'action' => 'marked_as_delivered']
            );
            
            // Process items into inventory
            foreach ($purchaseOrder->items as $orderItem) {
                // Find or create the item
                $item = Item::firstOrCreate(
                    ['name' => $orderItem->item_name],
                    [
                        'description' => 'Added from purchase order #' . $purchaseOrder->id,
                        'category_id' => 1 // Default category, can be changed later
                    ]
                );
                
                // Update inventory
                $inventory = Inventory::firstOrNew(['item_id' => $item->id]);
                $inventory->current_stock += $orderItem->quantity;
                $inventory->last_stocked_at = now();
                $inventory->supplier_name = $purchaseOrder->supplier->name;
                $inventory->purchase_order_id = $purchaseOrder->id;
                $inventory->save();
                
                // Create stock movement record for incoming stock
                \App\Models\StockMovement::create([
                    'item_id' => $item->id,
                    'from_location_id' => null, // Coming from supplier, not an internal location
                    'to_location_id' => null, // Going to central inventory, not a specific location
                    'quantity' => $orderItem->quantity,
                    'type' => 'in',
                    'user_id' => \Illuminate\Support\Facades\Auth::id(),
                    'notes' => 'Stock received from PO #' . $purchaseOrder->id . ' (' . $purchaseOrder->supplier->name . ')'
                ]);
            }
            
            DB::commit();

            return redirect()->route('inventory.purchase_orders.show', $purchaseOrder)
                ->with('success', 'Purchase order marked as delivered and items added to inventory.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update purchase order status: ' . $e->getMessage());
        }
    }

    public function markAsCanceled(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return back()->with('error', 'This purchase order has already been ' . $purchaseOrder->status . '.');
        }

        try {
            // Store old status for audit log
            $oldStatus = $purchaseOrder->status;
            
            $purchaseOrder->update([
                'status' => 'canceled',
            ]);
            
            // Create custom audit log for status change
            AuditService::createLog(
                'status_changed',
                'purchase_orders',
                $purchaseOrder->id,
                ['status' => $oldStatus],
                ['status' => 'canceled', 'action' => 'marked_as_canceled']
            );

            return redirect()->route('inventory.purchase_orders.show', $purchaseOrder)
                ->with('success', 'Purchase order marked as canceled.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update purchase order status: ' . $e->getMessage());
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        // Check if the purchase order can be deleted
        if ($purchaseOrder->status !== 'pending') {
            return redirect()->route('inventory.purchase_orders.index')
                ->with('error', 'Cannot delete a purchase order that has been delivered or canceled.');
        }
        
        try {
            DB::beginTransaction();
            
            // Delete purchase order items
            $purchaseOrder->items()->delete();
            
            // Delete purchase order
            $purchaseOrder->delete();
            
            DB::commit();
            
            return redirect()->route('inventory.purchase_orders.index')
                ->with('success', 'Purchase order deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete purchase order: ' . $e->getMessage());
        }
    }
}
