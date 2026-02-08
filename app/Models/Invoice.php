<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'appointment_id',
        'subscription_id',
        'invoice_number',
        'description',
        'amount',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'status',
        'due_date',
        'issued_date',
        'paid_date',
        'paid_at',
        'stripe_invoice_id',
        'notes',
        'treatment_type',
        'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'due_date' => 'date',
            'issued_date' => 'date',
            'paid_date' => 'date',
            'paid_at' => 'datetime',
            'reminder_sent_at' => 'datetime',
        ];
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->total_paid;
    }
}