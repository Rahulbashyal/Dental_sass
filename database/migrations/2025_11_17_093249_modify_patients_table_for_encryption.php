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
        Schema::table('patients', function (Blueprint $table) {
            $table->text('address')->nullable()->change();
            $table->text('emergency_contact_name')->nullable()->change();
            $table->text('emergency_contact_phone')->nullable()->change();
            $table->text('medical_history')->nullable()->change();
            $table->text('allergies')->nullable()->change();
            $table->text('insurance_provider')->nullable()->change();
            $table->text('insurance_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Reverting may result in data loss if the encrypted data is too long.
            // It's recommended to back up data before reverting this migration.
            $table->string('address')->nullable()->change();
            $table->string('emergency_contact_name')->nullable()->change();
            $table->string('emergency_contact_phone')->nullable()->change();
            $table->string('medical_history')->nullable()->change();
            $table->string('allergies')->nullable()->change();
            $table->string('insurance_provider')->nullable()->change();
            $table->string('insurance_number')->nullable()->change();
        });
    }
};