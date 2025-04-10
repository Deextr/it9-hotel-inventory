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
}
