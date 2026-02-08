<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    protected $fillable = [
        'clinic_id',
        'name',
        'email',
        'phone',
        'company',
        'status',
        'source',
        'notes',
        'potential_value',
        'assigned_to',
        'last_contacted_at'
    ];

    protected $casts = [
        'potential_value' => 'decimal:2',
        'last_contacted_at' => 'datetime'
    ];

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
