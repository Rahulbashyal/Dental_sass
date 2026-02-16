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
            $table->boolean('show_in_landing_menu')->default(true)->after('description');
            $table->boolean('show_services_menu')->default(true)->after('show_in_landing_menu');
            $table->boolean('show_team_menu')->default(true)->after('show_services_menu');
            $table->boolean('show_gallery_menu')->default(true)->after('show_team_menu');
            $table->boolean('show_contact_menu')->default(true)->after('show_gallery_menu');
            $table->boolean('show_booking_button')->default(true)->after('show_contact_menu');
            $table->string('booking_button_text')->default('Book Appointment')->after('show_booking_button');
            $table->string('booking_button_style')->default('primary')->after('booking_button_text');
        });
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn([
                'show_in_landing_menu',
                'show_services_menu',
                'show_team_menu',
                'show_gallery_menu',
                'show_contact_menu',
                'show_booking_button',
                'booking_button_text',
                'booking_button_style',
            ]);
        });
    }
};
