<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->latest()->paginate(10);
        return view('inventory.purchase_orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('inventory.purchase_orders.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
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
            foreach ($validated['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
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
        $purchaseOrder->load('items');
        return view('inventory.purchase_orders.edit', compact('purchaseOrder', 'suppliers'));
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
            'items.*.item_name' => 'required|string',
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
            foreach ($validated['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
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
            
            $purchaseOrder->update([
                'status' => 'delivered',
                'delivered_date' => now(),
            ]);
            
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
            $purchaseOrder->update([
                'status' => 'canceled',
            ]);

            return redirect()->route('inventory.purchase_orders.show', $purchaseOrder)
                ->with('success', 'Purchase order marked as canceled.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update purchase order status: ' . $e->getMessage());
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
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
