<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            ActivityLog::log('created', $model, $model->getAttributes());
        });

        static::updated(function ($model) {
            ActivityLog::log('updated', $model, [
                'old' => array_intersect_key($model->getOriginal(), $model->getDirty()),
                'new' => $model->getDirty()
            ]);
        });

        static::deleted(function ($model) {
            ActivityLog::log('deleted', $model);
        });
    }
}
