<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Clinic chains for multi-location support
        Schema::create('clinic_chains', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add chain_id to clinics table
        Schema::table('clinics', function (Blueprint $table) {
            $table->foreignId('chain_id')->nullable()->constrained('clinic_chains')->nullOnDelete();
            $table->boolean('is_main_location')->default(true);
        });

        // Cross-location staff assignments
        Schema::create('staff_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->json('permissions')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_locations');
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropForeign(['chain_id']);
            $table->dropColumn(['chain_id', 'is_main_location']);
        });
        Schema::dropIfExists('clinic_chains');
    }
};