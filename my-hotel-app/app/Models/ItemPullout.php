<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPullout extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'location_id',
        'quantity',
        'reason',
        'status',
        'user_id',
        'notes',
    ];

    /**
     * Get the item that was pulled out.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the location from which the item was pulled out.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the user who performed the pullout.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 