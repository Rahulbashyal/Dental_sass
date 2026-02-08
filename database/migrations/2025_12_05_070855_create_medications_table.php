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
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('category'); // antibiotic, painkiller, anti-inflammatory, etc.
            $table->text('description')->nullable();
            $table->json('common_dosages')->nullable(); // ["250mg", "500mg", "1g"]
            $table->text('side_effects')->nullable();
            $table->text('contraindications')->nullable();
            $table->string('manufacturer')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for search
            $table->index('name');
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
