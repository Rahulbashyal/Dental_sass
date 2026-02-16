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
            // Stats Section
            if (!Schema::hasColumn('landing_page_contents', 'stats_experience')) {
                $table->string('stats_experience', 20)->nullable()->after('nav_booking_cta');
            }
            if (!Schema::hasColumn('landing_page_contents', 'stats_experience_label')) {
                $table->string('stats_experience_label', 50)->nullable()->after('stats_experience');
            }
            if (!Schema::hasColumn('landing_page_contents', 'stats_patients')) {
                $table->string('stats_patients', 20)->nullable()->after('stats_experience_label');
            }
            if (!Schema::hasColumn('landing_page_contents', 'stats_patients_label')) {
                $table->string('stats_patients_label', 50)->nullable()->after('stats_patients');
            }
            if (!Schema::hasColumn('landing_page_contents', 'stats_success_rate')) {
                $table->string('stats_success_rate', 20)->nullable()->after('stats_patients_label');
            }
            if (!Schema::hasColumn('landing_page_contents', 'stats_success_rate_label')) {
                $table->string('stats_success_rate_label', 50)->nullable()->after('stats_success_rate');
            }
            if (!Schema::hasColumn('landing_page_contents', 'stats_emergency')) {
                $table->string('stats_emergency', 20)->nullable()->after('stats_success_rate_label');
            }
            if (!Schema::hasColumn('landing_page_contents', 'stats_emergency_label')) {
                $table->string('stats_emergency_label', 50)->nullable()->after('stats_emergency');
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
                'stats_experience',
                'stats_experience_label',
                'stats_patients',
                'stats_patients_label',
                'stats_success_rate',
                'stats_success_rate_label',
                'stats_emergency',
                'stats_emergency_label',
            ]);
        });
    }
};
