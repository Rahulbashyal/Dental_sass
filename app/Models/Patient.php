<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\EncryptedAttributes;
use App\Traits\AnonymizesData;
use App\Traits\MasksData;

use App\Traits\LogsActivity;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Patient extends Authenticatable
{
    use HasFactory, EncryptedAttributes, AnonymizesData, MasksData, LogsActivity, Notifiable;

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
        'photo',
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

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
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

    public function clinicalNotes()
    {
        return $this->hasMany(ClinicalNote::class);
    }

    public function consents()
    {
        return $this->hasMany(PatientConsent::class);
    }

    public function imagingStudies()
    {
        return $this->hasMany(ImagingStudy::class);
    }

    public function labOrders()
    {
        return $this->hasMany(LabOrder::class);
    }

    public function paymentPlans()
    {
        return $this->hasMany(PaymentPlan::class);
    }

    public function recurringAppointments()
    {
        return $this->hasMany(RecurringAppointment::class);
    }
}