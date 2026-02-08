<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'clinic_id', 
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'session_id',
        'description',
        'severity'
    ];
    
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
    
    public static function log($action, $model = null, $oldValues = null, $newValues = null, $description = null, $severity = 'info')
    {
        return self::create([
            'user_id' => auth()->id(),
            'clinic_id' => auth()->user()->clinic_id ?? null,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
            'description' => $description,
            'severity' => $severity
        ]);
    }
}
