<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. expenses ─────────────────────────────────────────────────────
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('clinic_id');
                $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('set null');
                $table->string('reference_number')->nullable();
                $table->string('category');
                $table->string('description');
                $table->decimal('amount', 10, 2);
                $table->enum('status', ['pending', 'paid'])->default('paid');
                $table->date('expense_date');
                $table->string('receipt_path')->nullable();
                $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamps();
            });
        }

        // ── 2. inventory_categories ──────────────────────────────────────────
        if (!Schema::hasTable('inventory_categories')) {
            Schema::create('inventory_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('clinic_id');
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // ── 3. suppliers ─────────────────────────────────────────────────────
        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('clinic_id');
                $table->string('name');
                $table->string('contact_person')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->timestamps();
            });
        }

        // ── 4. purchase_orders ───────────────────────────────────────────────
        if (!Schema::hasTable('purchase_orders')) {
            Schema::create('purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('clinic_id');
                $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
                $table->string('order_number')->unique();
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->enum('status', ['draft', 'ordered', 'received', 'cancelled'])->default('draft');
                $table->timestamp('ordered_at')->nullable();
                $table->timestamp('received_at')->nullable();
                $table->timestamps();
            });
        }

        // ── 5. purchase_order_items ──────────────────────────────────────────
        if (!Schema::hasTable('purchase_order_items')) {
            Schema::create('purchase_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
                $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
                $table->decimal('quantity', 10, 2);
                $table->decimal('unit_price', 10, 2);
                $table->decimal('total_price', 12, 2);
                $table->timestamps();
            });
        }

        // ── 6. equipment ─────────────────────────────────────────────────────
        if (!Schema::hasTable('equipment')) {
            Schema::create('equipment', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('clinic_id');
                $table->string('name');
                $table->string('model')->nullable();
                $table->string('serial_number')->nullable();
                $table->date('purchase_date')->nullable();
                $table->date('warranty_expiry')->nullable();
                $table->timestamp('last_maintenance_at')->nullable();
                $table->enum('status', ['operational', 'under_maintenance', 'retired'])->default('operational');
                $table->timestamps();
            });
        }
        
        // ── 7. update inventory_items to new structure if it hasn't been updated ──
        if (Schema::hasTable('inventory_items')) {
            Schema::table('inventory_items', function (Blueprint $table) {
                if (!Schema::hasColumn('inventory_items', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('clinic_id');
                    // We don't add constraint here yet to avoid errors if category_id is null
                }
                if (!Schema::hasColumn('inventory_items', 'unit')) {
                    $table->string('unit')->default('pcs')->after('description');
                }
                if (!Schema::hasColumn('inventory_items', 'current_stock')) {
                    // It already has 'quantity' in old structure, let's map it if needed
                    // For now just add columns if missing
                    $table->decimal('current_stock', 10, 2)->default(0)->after('unit');
                }
                if (!Schema::hasColumn('inventory_items', 'min_stock_level')) {
                    $table->decimal('min_stock_level', 10, 2)->default(0)->after('current_stock');
                }
                if (!Schema::hasColumn('inventory_items', 'unit_price')) {
                    $table->decimal('unit_price', 10, 2)->nullable()->after('min_stock_level');
                }
            });
        }
    }

    public function down(): void
    {
        // No down for master fix
    }
};
