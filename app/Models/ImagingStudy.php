<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class ImagingStudy extends Model
{
    use LogsActivity;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'dentist_id',
        'appointment_id',
        'type',
        'tooth_area',
        'clinical_indication',
        'findings',
        'radiologist_notes',
        'status',
        'study_date'
    ];

    protected $casts = [
        'study_date' => 'date'
    ];

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

    public function files()
    {
        return $this->hasMany(ImagingFile::class);
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'x_ray' => 'X-Ray',
            'cbct' => 'CBCT Scan',
            'panoramic' => 'Panoramic OPG',
            'periapical' => 'Periapical',
            'bitewing' => 'Bitewing',
            'cephalometric' => 'Cephalometric',
            'intraoral' => 'Intraoral Camera',
            default => ucfirst($this->type),
        };
    }
}
