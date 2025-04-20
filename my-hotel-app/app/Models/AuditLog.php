<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AuditLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'old_values',
        'new_values',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    /**
     * Get the user that performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get human-readable action name
     */
    public function getActionLabelAttribute()
    {
        $labels = [
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'login' => 'Logged in',
            'logout' => 'Logged out',
        ];

        return $labels[$this->action] ?? ucfirst($this->action);
    }

    /**
     * Get the badge color class based on action
     */
    public function getBadgeColorAttribute()
    {
        return match($this->action) {
            'created' => 'bg-green-100 text-green-800',
            'updated' => 'bg-blue-100 text-blue-800',
            'deleted' => 'bg-red-100 text-red-800',
            'login' => 'bg-purple-100 text-purple-800',
            'logout' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get a formatted table name for display
     */
    public function getTableLabelAttribute()
    {
        if ($this->action === 'login' || $this->action === 'logout') {
            return null;
        }
        
        return Str::title(str_replace('_', ' ', $this->table_name));
    }

    /**
     * Get a formatted description of the change
     */
    public function getDescriptionAttribute()
    {
        if ($this->action === 'login') {
            return 'User logged into the system';
        }

        if ($this->action === 'logout') {
            return 'User logged out of the system';
        }

        $tableName = $this->table_label;
        $recordId = $this->record_id;
        
        return match($this->action) {
            'created' => "Created new {$tableName} record #{$recordId}",
            'updated' => "Updated {$tableName} record #{$recordId}",
            'deleted' => "Deleted {$tableName} record #{$recordId}",
            default => ucfirst($this->action) . " {$tableName} record #{$recordId}"
        };
    }

    /**
     * Get a formatted representation of what changed
     */
    public function getChangesAttribute()
    {
        // For login/logout events, there are no changes to show
        if (in_array($this->action, ['login', 'logout', 'system'])) {
            return null;
        }

        // For creation, return all new values
        if ($this->action === 'created' && !empty($this->new_values)) {
            return ['created' => $this->new_values];
        }

        // For deletion, return the values that were deleted
        if ($this->action === 'deleted' && !empty($this->old_values)) {
            return ['deleted' => $this->old_values];
        }

        // For updates, compare old and new values to show what changed
        $changes = [];
        if (!empty($this->old_values) && !empty($this->new_values)) {
            foreach ($this->new_values as $key => $newValue) {
                $oldValue = $this->old_values[$key] ?? null;
                
                // Only include fields that actually changed
                if ($oldValue !== $newValue) {
                    $changes[$key] = [
                        'from' => $oldValue,
                        'to' => $newValue,
                    ];
                }
            }
        }

        return $changes;
    }
} 