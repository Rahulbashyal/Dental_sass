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
            // Gallery section
            if (!Schema::hasColumn('landing_page_contents', 'gallery_description')) {
                $table->text('gallery_description')->nullable()->after('gallery_title');
            }
            
            // Testimonials section
            if (!Schema::hasColumn('landing_page_contents', 'testimonials_description')) {
                $table->text('testimonials_description')->nullable()->after('testimonials_title');
            }
            
            // Contact section
            if (!Schema::hasColumn('landing_page_contents', 'contact_get_in_touch_title')) {
                $table->string('contact_get_in_touch_title', 255)->nullable()->after('contact_subtitle');
            }
            if (!Schema::hasColumn('landing_page_contents', 'contact_send_message_title')) {
                $table->string('contact_send_message_title', 255)->nullable()->after('contact_get_in_touch_title');
            }
            
            // Footer section
            if (!Schema::hasColumn('landing_page_contents', 'footer_tagline')) {
                $table->string('footer_tagline', 255)->nullable()->after('footer_copyright');
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
                'gallery_description',
                'testimonials_description',
                'contact_get_in_touch_title',
                'contact_send_message_title',
                'footer_tagline',
            ]);
        });
    }
};
