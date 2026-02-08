<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            // Module Configuration
            $table->json('enabled_modules')->default('["appointments", "patients", "invoicing", "reports"]');
            $table->json('enabled_roles')->default('["clinic_admin", "dentist", "receptionist", "accountant"]');
            
            // Feature Configuration
            $table->boolean('has_landing_page')->default(true);
            $table->boolean('has_crm')->default(false);
            $table->boolean('has_patient_portal')->default(true);
            $table->boolean('has_email_system')->default(true);
            $table->boolean('has_notifications')->default(true);
            $table->boolean('has_analytics')->default(true);
            $table->boolean('has_accounting')->default(true);
            $table->boolean('has_inventory')->default(false);
            $table->boolean('has_lab_integration')->default(false);
            $table->boolean('has_telemedicine')->default(false);
            
            // Subscription Configuration
            $table->string('subscription_tier')->default('basic'); // basic, professional, enterprise
            $table->integer('max_users')->default(5);
            $table->integer('max_patients')->default(1000);
            $table->integer('max_appointments_per_month')->default(500);
            $table->boolean('has_custom_branding')->default(false);
            $table->boolean('has_api_access')->default(false);
            $table->boolean('has_priority_support')->default(false);
            
            // Business Configuration
            $table->string('business_type')->default('dental_clinic'); // dental_clinic, orthodontic, oral_surgery, etc.
            $table->string('timezone')->default('Asia/Kathmandu');
            $table->string('currency')->default('NPR');
            $table->json('business_hours')->nullable();
            $table->json('appointment_settings')->nullable();
            
            // Integration Configuration
            $table->json('payment_gateways')->default('["cash", "bank_transfer"]');
            $table->json('sms_providers')->default('[]');
            $table->json('email_providers')->default('["smtp"]');
        });
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn([
                'enabled_modules', 'enabled_roles', 'has_landing_page', 'has_crm',
                'has_patient_portal', 'has_email_system', 'has_notifications',
                'has_analytics', 'has_accounting', 'has_inventory', 'has_lab_integration',
                'has_telemedicine', 'subscription_tier', 'max_users', 'max_patients',
                'max_appointments_per_month', 'has_custom_branding', 'has_api_access',
                'has_priority_support', 'business_type', 'timezone', 'currency',
                'business_hours', 'appointment_settings', 'payment_gateways',
                'sms_providers', 'email_providers'
            ]);
        });
    }
};