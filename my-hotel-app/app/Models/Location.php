<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

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
} 