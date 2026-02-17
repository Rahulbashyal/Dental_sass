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
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users');
            $table->json('recipients');
            $table->string('subject');
            $table->text('body');
            $table->json('attachments')->nullable();
            $table->enum('status', ['draft', 'sent', 'failed'])->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
