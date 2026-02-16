<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consent_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id');
            $table->string('title');
            $table->text('content'); // Markdown or HTML
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('patient_consents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('template_id')->constrained('consent_templates')->onDelete('cascade');
            $table->timestamp('signed_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('signature_path')->nullable(); // If image signature
            $table->enum('status', ['pending', 'signed', 'revoked'])->default('signed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_consents');
        Schema::dropIfExists('consent_templates');
    }
};
