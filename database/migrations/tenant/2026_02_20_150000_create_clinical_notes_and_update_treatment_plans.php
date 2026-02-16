<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinical_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('dentist_id')->constrained('users')->onDelete('cascade');
            $table->integer('tooth_number')->nullable();
            $table->string('surface')->nullable(); // M, D, O, L, B
            $table->string('condition')->nullable(); // Decay, Filling, Missing, etc.
            $table->text('note');
            $table->json('metadata')->nullable(); // For visual chart flags
            $table->timestamps();
        });

        Schema::table('treatment_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('treatment_plans', 'treatments')) {
                $table->json('treatments')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('treatment_plans', function (Blueprint $table) {
            $table->dropColumn('treatments');
        });
        Schema::dropIfExists('clinical_notes');
    }
};
