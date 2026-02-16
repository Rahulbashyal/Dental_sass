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
        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'appointment_duration')) {
                $table->integer('appointment_duration')->default(30)->after('currency');
            }
            if (!Schema::hasColumn('clinics', 'working_hours_start')) {
                $table->string('working_hours_start')->default('09:00')->after('appointment_duration');
            }
            if (!Schema::hasColumn('clinics', 'working_hours_end')) {
                $table->string('working_hours_end')->default('18:00')->after('working_hours_start');
            }
            if (!Schema::hasColumn('clinics', 'working_days')) {
                $table->json('working_days')->nullable()->after('working_hours_end');
            }
            if (!Schema::hasColumn('clinics', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable()->after('website_url');
            }
            if (!Schema::hasColumn('clinics', 'youtube_url')) {
                $table->string('youtube_url')->nullable()->after('linkedin_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn(['appointment_duration', 'working_hours_start', 'working_hours_end', 'working_days', 'linkedin_url', 'youtube_url']);
        });
    }
};
