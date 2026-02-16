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
        // Inventory & Equipment Optimization
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->index('clinic_id');
            $table->index(['clinic_id', 'category_id']);
            $table->index('current_stock');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->index('clinic_id');
            $table->index(['clinic_id', 'status']);
            $table->index('ordered_at');
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->index('clinic_id');
            $table->index(['clinic_id', 'status']);
            $table->index('warranty_expiry');
        });

        // CRM Optimization (Central & Tenant potentially)
        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (!Schema::hasColumn('leads', 'clinic_id')) {
                     // If leads is central, index what's there
                } else {
                    $table->index('clinic_id');
                    $table->index(['clinic_id', 'status']);
                }
                $table->index('status');
                $table->index('created_at');
            });
        }

        if (Schema::hasTable('campaigns')) {
            Schema::table('campaigns', function (Blueprint $table) {
                if (Schema::hasColumn('campaigns', 'clinic_id')) {
                    $table->index('clinic_id');
                    $table->index(['clinic_id', 'status']);
                }
                $table->index('status');
            });
        }

        // Email Logs Optimization
        if (Schema::hasTable('email_logs')) {
            Schema::table('email_logs', function (Blueprint $table) {
                $table->index('campaign_id');
                $table->index('lead_id');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropIndex(['clinic_id']);
            $table->dropIndex(['clinic_id', 'category_id']);
            $table->dropIndex(['current_stock']);
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropIndex(['clinic_id']);
            $table->dropIndex(['clinic_id', 'status']);
            $table->dropIndex(['ordered_at']);
        });

        Schema::table('equipment', function (Blueprint $table) {
            $table->dropIndex(['clinic_id']);
            $table->dropIndex(['clinic_id', 'status']);
            $table->dropIndex(['warranty_expiry']);
        });

        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (Schema::hasColumn('leads', 'clinic_id')) {
                    $table->dropIndex(['clinic_id']);
                    $table->dropIndex(['clinic_id', 'status']);
                }
                $table->dropIndex(['status']);
                $table->dropIndex(['created_at']);
            });
        }

        if (Schema::hasTable('campaigns')) {
            Schema::table('campaigns', function (Blueprint $table) {
                if (Schema::hasColumn('campaigns', 'clinic_id')) {
                    $table->dropIndex(['clinic_id']);
                    $table->dropIndex(['clinic_id', 'status']);
                }
                $table->dropIndex(['status']);
            });
        }

        if (Schema::hasTable('email_logs')) {
            Schema::table('email_logs', function (Blueprint $table) {
                $table->dropIndex(['campaign_id']);
                $table->dropIndex(['lead_id']);
                $table->dropIndex(['created_at']);
            });
        }
    }
};
