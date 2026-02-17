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
        // Add indexes for frequently queried columns
        Schema::table('users', function (Blueprint $table) {
            $table->index(['clinic_id', 'is_active']);
            $table->index(['email', 'is_active']);
            $table->index('created_at');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->index(['clinic_id', 'is_active']);
            $table->index(['clinic_id', 'email']);
            $table->index(['clinic_id', 'patient_id']);
            $table->index('created_at');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->index(['clinic_id', 'appointment_date']);
            $table->index(['clinic_id', 'status']);
            $table->index(['patient_id', 'status']);
            $table->index(['dentist_id', 'appointment_date']);
            $table->index('created_at');
        });

        Schema::table('clinics', function (Blueprint $table) {
            $table->index(['is_active', 'subscription_status']);
            $table->index('slug');
            $table->index('created_at');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->index(['clinic_id', 'status']);
            $table->index(['patient_id', 'status']);
            $table->index(['due_date', 'status']);
            $table->index('created_at');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['user_id', 'read_at']);
            $table->index(['user_id', 'created_at']);
        });

        // Add composite indexes for common query patterns
        Schema::table('appointments', function (Blueprint $table) {
            $table->index(['clinic_id', 'appointment_date', 'status'], 'appointments_clinic_date_status_idx');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->index(['clinic_id', 'created_at', 'is_active'], 'patients_clinic_created_active_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['clinic_id', 'is_active']);
            $table->dropIndex(['email', 'is_active']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropIndex(['clinic_id', 'is_active']);
            $table->dropIndex(['clinic_id', 'email']);
            $table->dropIndex(['clinic_id', 'patient_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex('patients_clinic_created_active_idx');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['clinic_id', 'appointment_date']);
            $table->dropIndex(['clinic_id', 'status']);
            $table->dropIndex(['patient_id', 'status']);
            $table->dropIndex(['dentist_id', 'appointment_date']);
            $table->dropIndex(['created_at']);
            $table->dropIndex('appointments_clinic_date_status_idx');
        });

        Schema::table('clinics', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'subscription_status']);
            $table->dropIndex(['slug']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['clinic_id', 'status']);
            $table->dropIndex(['patient_id', 'status']);
            $table->dropIndex(['due_date', 'status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'read_at']);
            $table->dropIndex(['user_id', 'created_at']);
        });
    }
};