<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'from_location_id',
        'to_location_id',
        'quantity',
        'type',
        'user_id',
        'notes',
    ];

    /**
     * Get the item associated with the stock movement.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the source location of the stock movement.
     */
    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    /**
     * Get the destination location of the stock movement.
     */
    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    /**
     * Get the user who performed the stock movement.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 