<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'clinic_id',
        'user_id',
        'action',
        'model_type',
        'model_id',
        'payload',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'payload' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function subject()
    {
        return $this->morphTo('model');
    }

    public static function log($action, $model = null, $payload = null)
    {
        return self::create([
            'clinic_id' => auth()->user()?->clinic_id,
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'payload' => $payload,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
