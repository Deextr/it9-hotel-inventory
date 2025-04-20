<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['category']);
        
        // Filter by status if requested
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Filter by category if requested
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }
        
        // Get all categories for the filter dropdown
        $categories = Category::pluck('name', 'id');
        
        // Paginate with more items per page
        $items = $query->orderBy('name')->paginate(10);
        
        // Preserve query parameters in pagination links
        $items->appends($request->query());
        
        return view('inventory.items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('inventory.items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
        ]);
        
        // Set is_active to true if not present
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        Item::create($validated);

        return redirect()->route('inventory.items.index')
            ->with('success', 'Item created successfully.');
    }

    public function edit(Item $item)
    {
        $categories = Category::where('is_active', true)
                     ->orWhere('id', $item->category_id) // Always include current category even if inactive
                     ->get();
        return view('inventory.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
        ]);
        
        // Set is_active to false if not present in request
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
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
    
    public function toggleStatus(Item $item)
    {
        $item->is_active = !$item->is_active;
        $item->save();
        
        $status = $item->is_active ? 'active' : 'inactive';
        return redirect()->route('inventory.items.index')
            ->with('success', "Item has been set to {$status}.");
    }
}
