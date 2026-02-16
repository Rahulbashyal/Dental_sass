<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landing_page_contents', function (Blueprint $table) {
            $table->json('hero_carousel_images')->nullable();
            $table->string('services_title')->nullable();
            $table->text('services_description')->nullable();
            $table->json('services_data')->nullable();
            $table->string('faq_title')->nullable();
            $table->json('faq_data')->nullable();
            $table->string('testimonials_title')->nullable();
            $table->json('testimonials_data')->nullable();
            $table->string('gallery_title')->nullable();
            $table->boolean('contact_form_enabled')->default(true);
            $table->json('custom_sections')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('landing_page_contents', function (Blueprint $table) {
            $table->dropColumn([
                'hero_carousel_images',
                'services_title',
                'services_description', 
                'services_data',
                'faq_title',
                'faq_data',
                'testimonials_title',
                'testimonials_data',
                'gallery_title',
                'contact_form_enabled',
                'custom_sections'
            ]);
        });
    }
};