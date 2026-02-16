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
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'appointment_id')) {
                $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('invoices', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('invoices', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('invoices', 'issued_date')) {
                $table->date('issued_date')->nullable();
            }
            if (!Schema::hasColumn('invoices', 'paid_date')) {
                $table->date('paid_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['appointment_id', 'description', 'discount_amount', 'issued_date', 'paid_date']);
        });
    }
};
