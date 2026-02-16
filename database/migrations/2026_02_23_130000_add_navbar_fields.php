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
        Schema::table('landing_page_contents', function (Blueprint $table) {
            // Navigation & Header
            if (!Schema::hasColumn('landing_page_contents', 'navbar_title')) {
                $table->string('navbar_title', 255)->nullable()->after('clinic_id');
            }
            if (!Schema::hasColumn('landing_page_contents', 'navbar_tagline')) {
                $table->string('navbar_tagline', 255)->nullable()->after('navbar_title');
            }
            if (!Schema::hasColumn('landing_page_contents', 'nav_home_label')) {
                $table->string('nav_home_label', 50)->nullable()->after('navbar_tagline');
            }
            if (!Schema::hasColumn('landing_page_contents', 'nav_about_label')) {
                $table->string('nav_about_label', 50)->nullable()->after('nav_home_label');
            }
            if (!Schema::hasColumn('landing_page_contents', 'nav_services_label')) {
                $table->string('nav_services_label', 50)->nullable()->after('nav_about_label');
            }
            if (!Schema::hasColumn('landing_page_contents', 'nav_gallery_label')) {
                $table->string('nav_gallery_label', 50)->nullable()->after('nav_services_label');
            }
            if (!Schema::hasColumn('landing_page_contents', 'nav_testimonials_label')) {
                $table->string('nav_testimonials_label', 50)->nullable()->after('nav_gallery_label');
            }
            if (!Schema::hasColumn('landing_page_contents', 'nav_faq_label')) {
                $table->string('nav_faq_label', 50)->nullable()->after('nav_testimonials_label');
            }
            if (!Schema::hasColumn('landing_page_contents', 'nav_contact_label')) {
                $table->string('nav_contact_label', 50)->nullable()->after('nav_faq_label');
            }
            if (!Schema::hasColumn('landing_page_contents', 'nav_booking_cta')) {
                $table->string('nav_booking_cta', 100)->nullable()->after('nav_contact_label');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_page_contents', function (Blueprint $table) {
            $table->dropColumn([
                'navbar_title',
                'navbar_tagline',
                'nav_home_label',
                'nav_about_label',
                'nav_services_label',
                'nav_gallery_label',
                'nav_testimonials_label',
                'nav_faq_label',
                'nav_contact_label',
                'nav_booking_cta',
            ]);
        });
    }
};
