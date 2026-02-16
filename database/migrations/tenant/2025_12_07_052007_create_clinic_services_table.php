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
        Schema::create('clinic_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id');
            
            // Basic Information
            $table->string('name');
            $table->string('slug');
            $table->text('description');
            $table->string('short_description', 500)->nullable();
            
            // Service Details
            $table->integer('duration_minutes')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('show_pricing')->default(true);
            $table->string('category', 100)->nullable();
            
            // Images
            $table->string('featured_image')->nullable();
            $table->json('before_after_images')->nullable();
            
            // SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            
            // Status & Ordering
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['clinic_id', 'is_active']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_services');
    }
};
