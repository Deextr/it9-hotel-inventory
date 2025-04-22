<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log a creation event
     */
    public static function logCreated(Model $model)
    {
        // Skip logging for AuditLog model to prevent recursion
        if ($model instanceof AuditLog) {
            return null;
        }
        
        return self::createLog(
            'created',
            $model->getTable(),
            $model->getKey(),
            null,
            self::getModelAttributes($model)
        );
    }
    
    /**
     * Log an update event
     */
    public static function logUpdated(Model $model, array $oldValues)
    {
        // Skip logging for AuditLog model to prevent recursion
        if ($model instanceof AuditLog) {
            return null;
        }
        
        return self::createLog(
            'updated',
            $model->getTable(),
            $model->getKey(),
            $oldValues,
            self::getModelAttributes($model)
        );
    }
    
    /**
     * Log a deletion event
     */
    public static function logDeleted(Model $model)
    {
        // Skip logging for AuditLog model to prevent recursion
        if ($model instanceof AuditLog) {
            return null;
        }
        
        return self::createLog(
            'deleted',
            $model->getTable(),
            $model->getKey(),
            self::getModelAttributes($model),
            null
        );
    }
    
    /**
     * Log a user login event
     */
    public static function logLogin()
    {
        $userId = Auth::id();
        
        return self::createLog(
            'login',
            'users',
            $userId,
            null,
            null
        );
    }
    
    /**
     * Log a user logout event
     */
    public static function logLogout($userId)
    {
        return self::createLog(
            'logout',
            'users',
            $userId,
            null,
            null
        );
    }
    
    /**
     * Create a custom audit log entry
     */
    public static function createLog($action, $tableName, $recordId, $oldValues = null, $newValues = null)
    {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
    
    /**
     * Get model data for logging, filtering out sensitive fields
     */
    private static function getModelAttributes(Model $model)
    {
        $attributes = $model->getAttributes();
        
        // Remove sensitive fields
        foreach (['password', 'remember_token'] as $field) {
            if (isset($attributes[$field])) {
                $attributes[$field] = '[REDACTED]';
            }
        }
        
        return $attributes;
    }
} 