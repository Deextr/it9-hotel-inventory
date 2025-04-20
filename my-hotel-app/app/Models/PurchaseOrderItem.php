<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'item_name',
        'quantity',
        'unit_price',
        'subtotal',
    ];
    
    protected $appends = [
        'item_id',
    ];
    
    // Initially null, will be set in the controller when needed
    protected $item_id = null;
    
    // Getter for the item_id attribute
    public function getItemIdAttribute()
    {
        return $this->item_id;
    }
    
    // Setter for the item_id attribute
    public function setItemIdAttribute($value)
    {
        $this->item_id = $value;
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
