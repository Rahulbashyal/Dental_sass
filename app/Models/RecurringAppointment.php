<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringAppointment extends Model
{
    protected $fillable = [
        'clinic_id', 'patient_id', 'dentist_id', 'frequency', 'interval_count',
        'days_of_week', 'appointment_time', 'type', 'start_date', 'end_date', 'is_active'
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'appointment_time' => 'datetime:H:i',
        'is_active' => 'boolean'
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }
}
