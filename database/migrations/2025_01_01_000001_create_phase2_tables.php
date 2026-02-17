<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Recurring appointments
        Schema::create('recurring_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dentist_id')->constrained('users')->cascadeOnDelete();
            $table->string('frequency'); // weekly, monthly, etc.
            $table->integer('interval_count')->default(1);
            $table->json('days_of_week')->nullable();
            $table->time('appointment_time');
            $table->string('type');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Appointment conflicts
        Schema::create('appointment_conflicts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('conflicting_appointment_id')->constrained('appointments')->cascadeOnDelete();
            $table->string('conflict_type'); // time_overlap, dentist_unavailable, etc.
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
        });

        // Waitlist
        Schema::create('waitlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dentist_id')->constrained('users')->cascadeOnDelete();
            $table->date('preferred_date');
            $table->time('preferred_time')->nullable();
            $table->string('appointment_type');
            $table->text('notes')->nullable();
            $table->enum('status', ['waiting', 'contacted', 'scheduled', 'cancelled'])->default('waiting');
            $table->timestamps();
        });

        // Payment plans
        Schema::create('payment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('down_payment', 10, 2)->default(0);
            $table->integer('installments');
            $table->decimal('installment_amount', 10, 2);
            $table->date('start_date');
            $table->enum('status', ['active', 'completed', 'defaulted'])->default('active');
            $table->timestamps();
        });

        // Payment plan installments
        Schema::create('payment_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_plan_id')->constrained()->cascadeOnDelete();
            $table->integer('installment_number');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_installments');
        Schema::dropIfExists('payment_plans');
        Schema::dropIfExists('waitlists');
        Schema::dropIfExists('appointment_conflicts');
        Schema::dropIfExists('recurring_appointments');
    }
};