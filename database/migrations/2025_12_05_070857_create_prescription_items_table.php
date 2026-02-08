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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            $table->foreignId('medication_id')->nullable()->constrained()->onDelete('set null');
            
            // Snapshot of medication details (in case medication is deleted/changed)
            $table->string('medication_name');
            $table->string('generic_name')->nullable();
            
            // Dosage information
            $table->string('dosage'); // e.g., "500mg", "10ml"
            $table->string('frequency'); // e.g., "2 times daily", "3 times daily", "as needed"
            $table->string('route')->default('Oral'); // Oral, Topical, Injectable
            $table->integer('duration_days'); // Number of days
            $table->integer('quantity'); // Total quantity to dispense
            
            // Additional instructions
            $table->text('instructions')->nullable(); // e.g., "Take after meals", "Apply to affected area"
            $table->text('precautions')->nullable(); // Any specific warnings
            
            $table->timestamps();
            
            // Indexes
            $table->index('prescription_id');
            $table->index('medication_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
