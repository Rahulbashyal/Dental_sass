<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentPlan extends Model
{
    protected $fillable = [
        'clinic_id',
        'patient_id',
        'invoice_id',
        'total_amount',
        'down_payment',
        'installments',
        'installment_amount',
        'start_date',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'installment_amount' => 'decimal:2',
        'start_date' => 'date',
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function paymentInstallments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class);
    }

    public function getRemainingAmountAttribute(): float
    {
        try {
            $paid = $this->installments()->where('status', 'paid')->sum('amount');
            return (float) ($this->total_amount - $this->down_payment - $paid);
        } catch (\Exception $e) {
            return (float) ($this->total_amount - $this->down_payment);
        }
    }

    public function getMonthlyAmountAttribute(): float
    {
        return (float) $this->installment_amount;
    }
}
