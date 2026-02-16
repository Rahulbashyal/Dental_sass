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
            // Section Visibility
            if (!Schema::hasColumn('landing_page_contents', 'show_hero')) {
                $table->boolean('show_hero')->default(true)->after('clinic_id');
            }
            if (!Schema::hasColumn('landing_page_contents', 'show_stats')) {
                $table->boolean('show_stats')->default(true)->after('show_hero');
            }
            if (!Schema::hasColumn('landing_page_contents', 'show_about')) {
                $table->boolean('show_about')->default(true)->after('show_stats');
            }
            if (!Schema::hasColumn('landing_page_contents', 'show_services')) {
                $table->boolean('show_services')->default(true)->after('show_about');
            }
            if (!Schema::hasColumn('landing_page_contents', 'show_gallery')) {
                $table->boolean('show_gallery')->default(true)->after('show_services');
            }
            if (!Schema::hasColumn('landing_page_contents', 'show_testimonials')) {
                $table->boolean('show_testimonials')->default(true)->after('show_gallery');
            }
            if (!Schema::hasColumn('landing_page_contents', 'show_faq')) {
                $table->boolean('show_faq')->default(true)->after('show_testimonials');
            }
            if (!Schema::hasColumn('landing_page_contents', 'show_contact')) {
                $table->boolean('show_contact')->default(true)->after('show_faq');
            }
            if (!Schema::hasColumn('landing_page_contents', 'show_footer')) {
                $table->boolean('show_footer')->default(true)->after('show_contact');
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
                'show_hero',
                'show_stats',
                'show_about',
                'show_services',
                'show_gallery',
                'show_testimonials',
                'show_faq',
                'show_contact',
                'show_footer',
            ]);
        });
    }
};
