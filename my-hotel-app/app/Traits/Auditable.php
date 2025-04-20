<?php

namespace App\Traits;

use App\Services\AuditService;

trait Auditable
{
    /**
     * Boot the trait.
     *
     * @return void
     */
    protected static function bootAuditable()
    {
        // When a model is created
        static::created(function ($model) {
            AuditService::logCreated($model);
        });

        // When a model is updated
        static::updated(function ($model) {
            // Get only the fields that changed
            $oldValues = array_intersect_key($model->getOriginal(), $model->getDirty());
            AuditService::logUpdated($model, $oldValues);
        });

        // When a model is deleted
        static::deleted(function ($model) {
            AuditService::logDeleted($model);
        });
    }
} 