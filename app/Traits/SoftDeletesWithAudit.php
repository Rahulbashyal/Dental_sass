<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\AuditLog;

trait SoftDeletesWithAudit
{
    use SoftDeletes;

    protected static function bootSoftDeletesWithAudit()
    {
        static::deleting(function ($model) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'soft_delete',
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'old_values' => $model->getOriginal(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        static::restoring(function ($model) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'restore',
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'new_values' => $model->getAttributes(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
    }
}