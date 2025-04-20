<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'floor_number',
        'area_type',
        'room_number',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'floor_number' => 'integer',
        'is_active' => 'boolean',
        'room_number' => 'integer',
    ];

    /**
     * Get a formatted display name
     */
    public function getFormattedNameAttribute()
    {
        return "Floor {$this->floor_number} - " . 
               ucfirst($this->area_type) . 
               ($this->room_number ? " {$this->room_number}" : "");
    }

    /**
     * Get the stock movements where this location is the source.
     */
    public function stockOutMovements()
    {
        return $this->hasMany(StockMovement::class, 'from_location_id');
    }

    /**
     * Get the stock movements where this location is the destination.
     */
    public function stockInMovements()
    {
        return $this->hasMany(StockMovement::class, 'to_location_id');
    }

    /**
     * Get all items in this location.
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'location_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
} 