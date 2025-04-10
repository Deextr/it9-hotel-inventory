<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    
    protected $table = 'inventory';
    
    protected $fillable = [
        'item_id',
        'current_stock',
        'reorder_level',
        'last_stocked_at',
        'supplier_name',
        'purchase_order_id'
    ];
    
    protected $casts = [
        'last_stocked_at' => 'datetime',
    ];
    
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
