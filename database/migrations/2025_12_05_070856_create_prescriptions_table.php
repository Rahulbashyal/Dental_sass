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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('dentist_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('prescription_number')->unique();
            
            // Dental-specific fields
            $table->string('chief_complaint')->nullable(); // Main dental issue
            $table->text('diagnosis'); // Dental diagnosis
            $table->text('treatment_provided')->nullable(); // Treatment done
            $table->text('dental_notes')->nullable(); // Dentist's notes
            
            // Patient health information (for prescription safety)
            $table->text('known_allergies')->nullable(); // Patient allergies
            $table->text('current_medications')->nullable(); // Other meds patient is taking
            $table->text('medical_conditions')->nullable(); // Relevant medical history
            
            // Prescription details
            $table->text('general_instructions')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->date('prescribed_date');
            $table->date('valid_until')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('prescription_number');
            $table->index('clinic_id');
            $table->index('patient_id');
            $table->index('dentist_id');
            $table->index('prescribed_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
