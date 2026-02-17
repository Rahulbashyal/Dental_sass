<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicSettings extends Model
{
    protected $fillable = [
        'clinic_id',
        // Email Notifications - Patient
        'email_patient_appointment_confirmation',
        'email_patient_prescription_issued',
        'email_patient_invoice_generated',
        'email_patient_welcome',
        // Email Notifications - Dentist
        'email_dentist_daily_schedule',
        'email_dentist_schedule_time',
        // Email Notifications - Receptionist
        'email_receptionist_new_appointment',
        // Email Notifications - Accountant
        'email_accountant_daily_summary',
        'email_accountant_summary_time',
        // Email Notifications - Admin
        'email_admin_weekly_report',
        'email_admin_report_day',
        'email_admin_report_time',
        // Scheduling Permissions
        'dentist_can_self_schedule',
        'receptionist_can_schedule_dentist',
        'require_appointment_approval',
        'allow_online_booking',
        // Feature Toggles
        'enable_prescriptions',
        'enable_invoicing',
        'enable_patient_portal',
        'enable_nepali_calendar',
        // Appointment Settings
        'appointment_buffer_minutes',
        'max_daily_appointments_per_dentist',
        'allow_weekend_appointments',
        // Language
        'notification_language',
    ];

    protected $casts = [
        // Booleans
        'email_patient_appointment_confirmation' => 'boolean',
        'email_patient_prescription_issued' => 'boolean',
        'email_patient_invoice_generated' => 'boolean',
        'email_patient_welcome' => 'boolean',
        'email_dentist_daily_schedule' => 'boolean',
        'email_receptionist_new_appointment' => 'boolean',
        'email_accountant_daily_summary' => 'boolean',
        'email_admin_weekly_report' => 'boolean',
        'dentist_can_self_schedule' => 'boolean',
        'receptionist_can_schedule_dentist' => 'boolean',
        'require_appointment_approval' => 'boolean',
        'allow_online_booking' => 'boolean',
        'enable_prescriptions' => 'boolean',
        'enable_invoicing' => 'boolean',
        'enable_patient_portal' => 'boolean',
        'enable_nepali_calendar' => 'boolean',
        'allow_weekend_appointments' => 'boolean',
        // Integers
        'appointment_buffer_minutes' => 'integer',
        'max_daily_appointments_per_dentist' => 'integer',
    ];

    /**
     * Get the clinic that owns the settings
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
