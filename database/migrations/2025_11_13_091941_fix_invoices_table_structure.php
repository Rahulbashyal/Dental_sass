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
            if (!Schema::hasColumn('invoices', 'patient_id')) {
                $table->foreignId('patient_id')->after('clinic_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('invoices', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->after('amount')->default(0);
            }
            if (!Schema::hasColumn('invoices', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->after('tax_amount');
            }
            if (!Schema::hasColumn('invoices', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['patient_id', 'tax_amount', 'total_amount', 'notes']);
        });
    }
};
