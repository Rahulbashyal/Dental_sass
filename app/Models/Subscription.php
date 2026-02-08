<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'clinic_id', 'subscription_plan_id', 'status', 'starts_at', 
        'ends_at', 'trial_ends_at', 'amount', 'stripe_subscription_id'
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'trial_ends_at' => 'date',
        'amount' => 'decimal:2'
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->ends_at->isFuture();
    }

    public function isOnTrial()
    {
        return $this->status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }
}