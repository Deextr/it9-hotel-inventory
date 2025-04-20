<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('items');
        
        // Filter by status if requested
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }
        
        // Order by name and paginate
        $categories = $query->orderBy('name')->paginate(10);
        
        // Preserve query parameters in pagination links
        $categories->appends($request->query());
        
        return view('inventory.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('inventory.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        // Set is_active to true if not present
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        Category::create($validated);

        return redirect()->route('inventory.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('inventory.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        // Set is_active to false if not present in request
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        $category->update($validated);

        return redirect()->route('inventory.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->items()->count() > 0) {
            return redirect()->route('inventory.categories.index')
                ->with('error', 'Cannot delete category with associated items.');
        }

        $category->delete();
        return redirect()->route('inventory.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
    
    public function toggleStatus(Category $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();
        
        $status = $category->is_active ? 'active' : 'inactive';
        return redirect()->route('inventory.categories.index')
            ->with('success', "Category has been set to {$status}.");
    }
}
