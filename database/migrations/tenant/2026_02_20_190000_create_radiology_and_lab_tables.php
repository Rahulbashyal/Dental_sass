<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Radiology / Imaging Studies
        Schema::create('imaging_studies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('dentist_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['x_ray', 'cbct', 'panoramic', 'periapical', 'bitewing', 'cephalometric', 'intraoral']);
            $table->string('tooth_area')->nullable(); // e.g., "Upper Left Quadrant"
            $table->text('clinical_indication')->nullable();
            $table->text('findings')->nullable();
            $table->text('radiologist_notes')->nullable();
            $table->enum('status', ['ordered', 'captured', 'reported', 'reviewed'])->default('ordered');
            $table->date('study_date')->nullable();
            $table->timestamps();
        });

        // Imaging Files (multiple per study)
        Schema::create('imaging_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imaging_study_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Lab Orders
        Schema::create('lab_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('dentist_id')->constrained('users')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->string('lab_name')->nullable();
            $table->enum('category', ['impression', 'crown', 'bridge', 'denture', 'bleaching_tray', 'night_guard', 'orthodontic', 'other']);
            $table->text('instructions');
            $table->json('materials')->nullable(); // e.g. ["PFM", "Zirconia"]
            $table->string('shade')->nullable(); // for crowns/veneers
            $table->date('sent_date')->nullable();
            $table->date('expected_return_date')->nullable();
            $table->date('received_date')->nullable();
            $table->decimal('lab_cost', 10, 2)->nullable();
            $table->enum('status', ['draft', 'sent', 'in_progress', 'received', 'fitted'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_orders');
        Schema::dropIfExists('imaging_files');
        Schema::dropIfExists('imaging_studies');
    }
};
