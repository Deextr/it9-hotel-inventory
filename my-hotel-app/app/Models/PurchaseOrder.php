<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class PurchaseOrder extends Model
{
    use Auditable;
    
    protected $fillable = [
        'supplier_id',
        'order_date',
        'status',
        'delivered_date',
        'total_amount',
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivered_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
