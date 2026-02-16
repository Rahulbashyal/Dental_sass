<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicalNote extends Model
{
    protected $fillable = [
        'clinic_id',
        'patient_id',
        'dentist_id',
        'tooth_number',
        'surface',
        'condition',
        'note',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'json'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
