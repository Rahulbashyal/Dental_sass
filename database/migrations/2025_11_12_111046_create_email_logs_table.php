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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('to_email');
            $table->string('subject');
            $table->text('body');
            $table->enum('status', ['sent', 'failed', 'bounced', 'opened', 'clicked'])->default('sent');
            $table->foreignId('campaign_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('lead_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('sent_at');
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
