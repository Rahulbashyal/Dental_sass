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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['email', 'sms', 'social_media', 'advertisement'])->default('email');
            $table->enum('status', ['draft', 'active', 'paused', 'completed'])->default('draft');
            $table->json('target_audience')->nullable();
            $table->decimal('budget', 10, 2)->nullable();
            $table->integer('total_sent')->default(0);
            $table->integer('total_opened')->default(0);
            $table->integer('total_clicked')->default(0);
            $table->integer('total_converted')->default(0);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
