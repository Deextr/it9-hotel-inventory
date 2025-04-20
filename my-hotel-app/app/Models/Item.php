<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }
    
    // Helper method to get current stock
    public function getCurrentStock()
    {
        return $this->inventory ? $this->inventory->current_stock : 0;
    }

    /**
     * Get all locations where this item is stored.
     */
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'location_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /**
     * Get all stock movements for this item.
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get quantity of this item in a specific location
     */
    public function getQuantityInLocation($locationId)
    {
        $locationItem = $this->locations()->where('location_id', $locationId)->first();
        return $locationItem ? $locationItem->pivot->quantity : 0;
    }

    /**
     * Get total quantity across all locations
     */
    public function getTotalQuantityAttribute()
    {
        return $this->locations()->sum('quantity');
    }
}
