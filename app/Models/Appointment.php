<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Appointment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'clinic_id',
        'branch_id',
        'patient_id',
        'dentist_id',
        'appointment_date',
        'appointment_time',
        'duration',
        'type',
        'status', // scheduled, confirmed, arrived, in_progress, completed, cancelled, no_show
        'notes',
        'treatment_cost',
        'payment_status',
        'checked_in_at',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'appointment_time' => 'datetime',
            'duration' => 'integer',
            'treatment_cost' => 'decimal:2',
            'checked_in_at' => 'datetime',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }
}