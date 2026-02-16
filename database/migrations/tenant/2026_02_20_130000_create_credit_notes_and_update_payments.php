<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id');
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('credit_note_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->text('reason')->nullable();
            $table->enum('status', ['applied', 'refunded', 'cancelled'])->default('applied');
            $table->timestamps();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->boolean('is_refund')->default(false)->after('status');
            $table->unsignedBigInteger('related_payment_id')->nullable()->after('is_refund');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['is_refund', 'related_payment_id']);
        });
        Schema::dropIfExists('credit_notes');
    }
};
