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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'CoreDental'
            $table->string('display_name');  // e.g., 'Core Dental Services'
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_core')->default(false); // If true, cannot be disabled easily
            $table->json('dependencies')->nullable(); // List of other module names
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
