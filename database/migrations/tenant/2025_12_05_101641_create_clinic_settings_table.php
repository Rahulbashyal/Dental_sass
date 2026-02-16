<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clinic_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id');
            
            // Email Notification Settings - Patient
            $table->boolean('email_patient_appointment_confirmation')->default(true);
            $table->boolean('email_patient_prescription_issued')->default(true);
            $table->boolean('email_patient_invoice_generated')->default(true);
            $table->boolean('email_patient_welcome')->default(true);
            
            // Email Notification Settings - Dentist
            $table->boolean('email_dentist_daily_schedule')->default(true);
            $table->time('email_dentist_schedule_time')->default('07:00:00');
            
            // Email Notification Settings - Receptionist
            $table->boolean('email_receptionist_new_appointment')->default(true);
            
            // Email Notification Settings - Accountant
            $table->boolean('email_accountant_daily_summary')->default(true);
            $table->time('email_accountant_summary_time')->default('18:00:00');
            
            // Email Notification Settings - Admin
            $table->boolean('email_admin_weekly_report')->default(true);
            $table->enum('email_admin_report_day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])->default('monday');
            $table->time('email_admin_report_time')->default('09:00:00');
            
            // Scheduling Permissions
            $table->boolean('dentist_can_self_schedule')->default(true);
            $table->boolean('receptionist_can_schedule_dentist')->default(true);
            $table->boolean('require_appointment_approval')->default(false);
            $table->boolean('allow_online_booking')->default(true);
            
            // Feature Toggles
            $table->boolean('enable_prescriptions')->default(true);
            $table->boolean('enable_invoicing')->default(true);
            $table->boolean('enable_patient_portal')->default(false);
            $table->boolean('enable_nepali_calendar')->default(true);
            
            // Appointment Settings
            $table->integer('appointment_buffer_minutes')->default(15);
            $table->integer('max_daily_appointments_per_dentist')->default(20);
            $table->boolean('allow_weekend_appointments')->default(false);
            
            // Language Settings
            $table->enum('notification_language', ['en', 'ne'])->default('en');
            
            $table->timestamps();
            
            // Unique constraint - one settings record per clinic
            $table->unique('clinic_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_settings');
    }
};
