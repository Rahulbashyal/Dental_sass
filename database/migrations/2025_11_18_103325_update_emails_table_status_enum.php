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
        Schema::table('emails', function (Blueprint $table) {
            // Update the status enum to include 'pending' as a valid status
            $table->enum('status', ['draft', 'pending', 'sent', 'failed'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emails', function (Blueprint $table) {
            // Revert to original enum
            $table->enum('status', ['draft', 'sent', 'failed'])->default('draft')->change();
        });
    }
};
