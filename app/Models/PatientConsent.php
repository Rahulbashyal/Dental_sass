<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientConsent extends Model
{
    protected $fillable = [
        'clinic_id',
        'patient_id',
        'template_id',
        'signed_at',
        'ip_address',
        'signature_path',
        'status'
    ];

    protected $casts = [
        'signed_at' => 'datetime'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function template()
    {
        return $this->belongsTo(ConsentTemplate::class, 'template_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
