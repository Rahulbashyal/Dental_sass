<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class LabOrder extends Model
{
    use LogsActivity;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'dentist_id',
        'order_number',
        'lab_name',
        'category',
        'instructions',
        'materials',
        'shade',
        'sent_date',
        'expected_return_date',
        'received_date',
        'lab_cost',
        'status',
        'notes'
    ];

    protected $casts = [
        'materials' => 'json',
        'sent_date' => 'date',
        'expected_return_date' => 'date',
        'received_date' => 'date',
        'lab_cost' => 'decimal:2'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }

    public function isOverdue(): bool
    {
        return $this->expected_return_date
            && $this->expected_return_date->isPast()
            && !in_array($this->status, ['received', 'fitted']);
    }
}
