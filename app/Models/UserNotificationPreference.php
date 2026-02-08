<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'receive_daily_schedule',
        'receive_appointment_alerts',
        'receive_financial_summaries',
        'receive_weekly_reports',
        'email_enabled',
        'sms_enabled',
    ];

    protected $casts = [
        'receive_daily_schedule' => 'boolean',
        'receive_appointment_alerts' => 'boolean',
        'receive_financial_summaries' => 'boolean',
        'receive_weekly_reports' => 'boolean',
        'email_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
    ];

    /**
     * Get the user that owns the notification preferences
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
