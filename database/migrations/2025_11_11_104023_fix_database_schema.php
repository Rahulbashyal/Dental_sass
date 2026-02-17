<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make patient_id nullable and auto-generated
        Schema::table('patients', function (Blueprint $table) {
            $table->string('patient_id')->nullable()->change();
        });

        // Make dentist_id nullable in appointments
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('dentist_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('patient_id')->nullable(false)->change();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('dentist_id')->nullable(false)->change();
        });
    }
};