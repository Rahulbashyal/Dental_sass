<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'medication_id',
        'medication_name',
        'generic_name',
        'dosage',
        'frequency',
        'route',
        'duration_days',
        'quantity',
        'instructions',
        'precautions',
        'status',
        'dispensed_at',
    ];

    protected $casts = [
        'dispensed_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    /**
     * Calculate total tablets/units needed
     */
    public function getTotalQuantityAttribute()
    {
        // Extract number from frequency (e.g., "2 times daily" -> 2)
        preg_match('/(\d+)/', $this->frequency, $matches);
        $timesPerDay = $matches[1] ?? 1;
        
        return $timesPerDay * $this->duration_days;
    }
}
