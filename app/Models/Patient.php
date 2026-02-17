<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\EncryptedAttributes;
use App\Traits\AnonymizesData;
use App\Traits\MasksData;

class Patient extends Model
{
    use HasFactory, EncryptedAttributes, AnonymizesData, MasksData;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'medical_history',
        'allergies',
        'insurance_provider',
        'insurance_number',
        'is_active',
    ];

    protected $encrypted = [
        'medical_history',
        'allergies', 
        'insurance_provider',
        'insurance_number',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone'
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function treatmentPlans()
    {
        return $this->hasMany(TreatmentPlan::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}