<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'dentist_id',
        'appointment_id',
        'prescription_number',
        'chief_complaint',
        'diagnosis',
        'treatment_provided',
        'dental_notes',
        'known_allergies',
        'current_medications',
        'medical_conditions',
        'general_instructions',
        'status',
        'prescribed_date',
        'valid_until',
    ];

    protected $casts = [
        'prescribed_date' => 'date',
        'valid_until' => 'date',
    ];

    /**
     * Boot the model and generate prescription number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($prescription) {
            if (!$prescription->prescription_number) {
                $prescription->prescription_number = self::generatePrescriptionNumber();
            }
        });
    }

    /**
     * Generate unique prescription number
     */
    public static function generatePrescriptionNumber()
    {
        $today = now()->format('Ymd');
        $count = self::whereDate('created_at', now()->toDateString())->count() + 1;
        return 'PRX-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Relationships
     */
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

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeForClinic($query, $clinicId)
    {
        return $query->where('clinic_id', $clinicId);
    }
}
