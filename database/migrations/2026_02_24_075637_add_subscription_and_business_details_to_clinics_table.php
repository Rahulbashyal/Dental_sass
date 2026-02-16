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
        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'subscription_price')) {
                $table->decimal('subscription_price', 10, 2)->nullable()->after('subscription_expires_at');
            }
            if (!Schema::hasColumn('clinics', 'subscription_features')) {
                $table->json('subscription_features')->nullable()->after('subscription_price');
            }
            if (!Schema::hasColumn('clinics', 'business_type_detail')) {
                $table->string('business_type_detail')->nullable()->after('business_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn(['subscription_price', 'subscription_features', 'business_type_detail']);
        });
    }
};
