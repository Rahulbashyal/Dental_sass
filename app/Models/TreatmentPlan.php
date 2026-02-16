<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'dentist_id',
        'title',
        'description',
        'treatments',
        'estimated_cost',
        'estimated_duration',
        'priority',
        'status'
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'treatments' => 'json'
    ];

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