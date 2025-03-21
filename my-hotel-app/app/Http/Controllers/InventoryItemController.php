<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class InventoryItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['category'])->paginate(10);
        return view('inventory.items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('inventory.items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'minimum_stock_level' => 'required|integer|min:0',
            'unit_of_measure' => 'required|string|max:50',
        ]);

        // Generate SKU
        $validated['sku'] = Item::generateSKU($request->category_id);

        Item::create($validated);

        return redirect()->route('inventory.items.index')
            ->with('success', 'Item created successfully.');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('inventory.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'minimum_stock_level' => 'required|integer|min:0',
            'unit_of_measure' => 'required|string|max:50',
        ]);

        // Generate new SKU only if category changed
        if ($item->category_id != $request->category_id) {
            $validated['sku'] = Item::generateSKU($request->category_id);
        }

        $item->update($validated);

        return redirect()->route('inventory.items.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('inventory.items.index')
            ->with('success', 'Item deleted successfully.');
    }
}
