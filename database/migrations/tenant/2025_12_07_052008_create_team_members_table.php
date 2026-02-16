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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Link to staff user if exists
            
            // Basic Information
            $table->string('name');
            $table->string('title'); // Dr., Dentist, Hygienist, etc.
            $table->string('specialization')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            
            // Credentials
            $table->text('education')->nullable();
            $table->integer('experience_years')->nullable();
            $table->string('languages')->nullable(); // Comma-separated
            
            // Availability
            $table->json('available_days')->nullable(); // ['monday', 'tuesday', ...]
            
            // Contact (optional - public facing)
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            
            // Social Media
            $table->json('social_links')->nullable(); // {facebook, instagram, linkedin, etc}
            
            // Display Settings
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['clinic_id', 'is_active']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
