<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('landing_page_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id')->nullable();
            
            // Hero Section
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('hero_cta_primary')->nullable();
            $table->string('hero_cta_secondary')->nullable();
            
            // Trust Indicators
            $table->string('trust_clinics')->nullable();
            $table->string('trust_patients')->nullable();
            $table->string('trust_appointments')->nullable();
            $table->string('trust_uptime')->nullable();
            $table->string('trust_revenue')->nullable();
            
            // About Section
            $table->string('about_title')->nullable();
            $table->text('about_description')->nullable();
            $table->string('about_image')->nullable();
            
            // Gallery Section
            $table->json('gallery_images')->nullable();
            
            // Company Info
            $table->string('company_name')->nullable();
            $table->string('company_tagline')->nullable();
            $table->string('company_rating')->nullable();
            $table->text('company_description')->nullable();
            $table->string('company_logo')->nullable();
            
            // Contact Section
            $table->string('contact_title')->nullable();
            $table->string('contact_subtitle')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_address')->nullable();
            
            // Footer
            $table->text('footer_description')->nullable();
            $table->string('footer_copyright')->nullable();
            
            // Theme & Styling
            $table->string('theme_primary_color')->nullable();
            $table->string('theme_secondary_color')->nullable();
            $table->string('theme_accent_color')->nullable();
            $table->string('theme_template')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['clinic_id', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('landing_page_contents');
    }
};