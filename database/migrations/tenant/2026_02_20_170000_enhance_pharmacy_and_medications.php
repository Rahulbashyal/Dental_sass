<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            if (!Schema::hasColumn('medications', 'current_stock')) {
                $table->integer('current_stock')->default(0)->after('is_active');
            }
            if (!Schema::hasColumn('medications', 'min_stock_level')) {
                $table->integer('min_stock_level')->default(5)->after('current_stock');
            }
        });

        Schema::table('prescription_items', function (Blueprint $table) {
            if (!Schema::hasColumn('prescription_items', 'status')) {
                $table->enum('status', ['prescribed', 'dispensed'])->default('prescribed')->after('quantity');
            }
            if (!Schema::hasColumn('prescription_items', 'dispensed_at')) {
                $table->timestamp('dispensed_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->dropColumn(['status', 'dispensed_at']);
        });
        Schema::table('medications', function (Blueprint $table) {
            $table->dropColumn(['current_stock', 'min_stock_level']);
        });
    }
};
