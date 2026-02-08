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
        Schema::create('user_notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Email Preferences by Type
            $table->boolean('receive_daily_schedule')->default(true);
            $table->boolean('receive_appointment_alerts')->default(true);
            $table->boolean('receive_financial_summaries')->default(true);
            $table->boolean('receive_weekly_reports')->default(true);
            
            // Notification Channels
            $table->boolean('email_enabled')->default(true);
            $table->boolean('sms_enabled')->default(false);
            
            $table->timestamps();
            
            // Unique constraint - one preference record per user
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notification_preferences');
    }
};
