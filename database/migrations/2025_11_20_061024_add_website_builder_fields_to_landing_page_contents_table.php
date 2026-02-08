<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landing_page_contents', function (Blueprint $table) {
            // Hero section fields
            $table->string('hero_video_url')->nullable()->after('hero_cta_secondary');
            
            // About section fields
            $table->integer('about_years_experience')->nullable()->after('about_description');
            $table->string('about_doctor_name')->nullable()->after('about_years_experience');
            
            // Services section fields (if not exists)
            if (!Schema::hasColumn('landing_page_contents', 'services_title')) {
                $table->string('services_title')->nullable()->after('about_doctor_name');
            }
            if (!Schema::hasColumn('landing_page_contents', 'services_description')) {
                $table->string('services_description')->nullable()->after('services_title');
            }
            
            // Gallery section fields
            $table->string('gallery_layout')->default('grid')->after('gallery_title');
            
            // Testimonials section fields
            if (!Schema::hasColumn('landing_page_contents', 'testimonials_title')) {
                $table->string('testimonials_title')->nullable()->after('gallery_layout');
            }
            $table->string('testimonials_style')->default('cards')->after('testimonials_title');
            
            // FAQ section fields
            if (!Schema::hasColumn('landing_page_contents', 'faq_title')) {
                $table->string('faq_title')->nullable()->after('testimonials_style');
            }
            $table->string('faq_description')->nullable()->after('faq_title');
            
            // Contact section enhancements
            $table->text('business_hours')->nullable()->after('contact_address');
            $table->string('google_maps_url')->nullable()->after('business_hours');
            
            // Theme enhancements
            $table->string('theme_font_family')->default('Inter')->after('theme_accent_color');
            
            // Analytics and tracking
            $table->string('google_analytics_id')->nullable()->after('meta_keywords');
            $table->string('facebook_pixel_id')->nullable()->after('google_analytics_id');
            
            // Footer enhancements
            if (!Schema::hasColumn('landing_page_contents', 'footer_description')) {
                $table->text('footer_description')->nullable()->after('facebook_pixel_id');
            }
            if (!Schema::hasColumn('landing_page_contents', 'footer_copyright')) {
                $table->string('footer_copyright')->nullable()->after('footer_description');
            }
            
            // Social media links
            $table->string('social_facebook')->nullable()->after('footer_copyright');
            $table->string('social_instagram')->nullable()->after('social_facebook');
            $table->string('social_twitter')->nullable()->after('social_instagram');
            $table->string('social_linkedin')->nullable()->after('social_twitter');
        });
    }

    public function down(): void
    {
        Schema::table('landing_page_contents', function (Blueprint $table) {
            $table->dropColumn([
                'hero_video_url',
                'about_years_experience',
                'about_doctor_name',
                'gallery_layout',
                'testimonials_style',
                'faq_description',
                'business_hours',
                'google_maps_url',
                'theme_font_family',
                'google_analytics_id',
                'facebook_pixel_id',
                'social_facebook',
                'social_instagram',
                'social_twitter',
                'social_linkedin'
            ]);
        });
    }
};