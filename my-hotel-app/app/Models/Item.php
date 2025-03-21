<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'sku',
        'minimum_stock_level',
        'unit_of_measure',
    ];

    public static function generateSKU($categoryId)
    {
        $category = Category::find($categoryId);
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $category->name), 0, 3));
        $year = date('y');
        
        // Get the last item with this category and year
        $lastItem = self::where('sku', 'LIKE', "{$prefix}{$year}%")
            ->orderBy('sku', 'desc')
            ->first();
        
        if ($lastItem) {
            // Extract the number from the last SKU and increment it
            $lastNumber = intval(substr($lastItem->sku, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // Format: AAA-YY-0001 (Category prefix - Year - Sequential number)
        return sprintf("%s%s%04d", $prefix, $year, $newNumber);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    public function isLowStock()
    {
        return $this->stock_level <= $this->minimum_stock_level;
    }

    public function getCurrentStock()
    {
        return $this->inventory->sum('quantity');
    }

    public function getStockByLocation($locationId)
    {
        return $this->inventory()
            ->where('location_id', $locationId)
            ->sum('quantity');
    }
    
}
