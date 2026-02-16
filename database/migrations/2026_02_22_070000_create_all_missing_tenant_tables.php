<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. vendors ──────────────────────────────────────────────────────
        if (!Schema::hasTable('vendors')) {
            Schema::create('vendors', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('clinic_id');
                $table->string('name');
                $table->string('contact_person')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('category')->nullable();
                $table->timestamps();
            });
        }

        // ── 2. expenses – add missing columns ────────────────────────────────
        if (Schema::hasTable('expenses')) {
            Schema::table('expenses', function (Blueprint $table) {
                if (!Schema::hasColumn('expenses', 'vendor_id')) {
                    $table->foreignId('vendor_id')->nullable()->after('clinic_id')->constrained()->onDelete('set null');
                }
                if (!Schema::hasColumn('expenses', 'reference_number')) {
                    $table->string('reference_number')->nullable()->after('vendor_id');
                }
                if (!Schema::hasColumn('expenses', 'status')) {
                    $table->enum('status', ['pending', 'paid'])->default('paid')->after('amount');
                }
            });
        }

        // ── 3. chairs ────────────────────────────────────────────────────────
        if (!Schema::hasTable('chairs')) {
            Schema::create('chairs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('clinic_id');
                $table->string('name');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // ── 4. appointments – chair_id ────────────────────────────────────────
        if (Schema::hasTable('appointments') && !Schema::hasColumn('appointments', 'chair_id')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->foreignId('chair_id')->nullable()->after('dentist_id')->constrained()->onDelete('set null');
            });
        }

        // ── 5. credit_notes ───────────────────────────────────────────────────
        if (!Schema::hasTable('credit_notes')) {
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
        }

        // ── 6. payments – refund columns ──────────────────────────────────────
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'is_refund')) {
                    $table->boolean('is_refund')->default(false)->after('status');
                }
                if (!Schema::hasColumn('payments', 'related_payment_id')) {
                    $table->unsignedBigInteger('related_payment_id')->nullable()->after('is_refund');
                }
            });
        }

        // ── 7. branch_id on financial tables ─────────────────────────────────
        if (Schema::hasTable('invoices') && !Schema::hasColumn('invoices', 'branch_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('clinic_id')->constrained()->onDelete('set null');
            });
        }
        if (Schema::hasTable('expenses') && !Schema::hasColumn('expenses', 'branch_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('clinic_id')->constrained()->onDelete('set null');
            });
        }
        if (Schema::hasTable('payments') && !Schema::hasColumn('payments', 'branch_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('clinic_id')->constrained()->onDelete('set null');
            });
        }

        // ── 8. invoice_items ──────────────────────────────────────────────────
        if (!Schema::hasTable('invoice_items')) {
            Schema::create('invoice_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
                $table->string('description');
                $table->integer('quantity')->default(1);
                $table->decimal('unit_price', 10, 2);
                $table->decimal('amount', 10, 2);
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        // ── 9. consent_templates + patient_consents ───────────────────────────
        if (!Schema::hasTable('consent_templates')) {
            Schema::create('consent_templates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('clinic_id');
                $table->string('title');
                $table->text('content');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('patient_consents')) {
            Schema::create('patient_consents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('clinic_id');
                $table->foreignId('patient_id')->constrained()->onDelete('cascade');
                $table->foreignId('template_id')->constrained('consent_templates')->onDelete('cascade');
                $table->timestamp('signed_at')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('signature_path')->nullable();
                $table->enum('status', ['pending', 'signed', 'revoked'])->default('signed');
                $table->timestamps();
            });
        }

        // ── 10. medications – pharmacy columns ────────────────────────────────
        if (Schema::hasTable('medications')) {
            Schema::table('medications', function (Blueprint $table) {
                if (!Schema::hasColumn('medications', 'current_stock')) {
                    $table->integer('current_stock')->default(0)->after('is_active');
                }
                if (!Schema::hasColumn('medications', 'min_stock_level')) {
                    $table->integer('min_stock_level')->default(5)->after('current_stock');
                }
            });
        }

        // ── 11. prescription_items – dispense columns ─────────────────────────
        if (Schema::hasTable('prescription_items')) {
            Schema::table('prescription_items', function (Blueprint $table) {
                if (!Schema::hasColumn('prescription_items', 'status')) {
                    $table->enum('status', ['prescribed', 'dispensed'])->default('prescribed')->after('quantity');
                }
                if (!Schema::hasColumn('prescription_items', 'dispensed_at')) {
                    $table->timestamp('dispensed_at')->nullable()->after('status');
                }
            });
        }

        // ── 12. activity_logs ─────────────────────────────────────────────────
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('clinic_id')->nullable();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->string('action');
                $table->string('model_type')->nullable();
                $table->unsignedBigInteger('model_id')->nullable();
                $table->json('payload')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamps();

                $table->index('clinic_id');
                $table->index(['model_type', 'model_id']);
            });
        }

        // ── 13. imaging_studies + imaging_files + lab_orders ──────────────────
        if (!Schema::hasTable('imaging_studies')) {
            Schema::create('imaging_studies', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('clinic_id');
                $table->foreignId('patient_id')->constrained()->onDelete('cascade');
                $table->foreignId('dentist_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
                $table->enum('type', ['x_ray', 'cbct', 'panoramic', 'periapical', 'bitewing', 'cephalometric', 'intraoral']);
                $table->string('tooth_area')->nullable();
                $table->text('clinical_indication')->nullable();
                $table->text('findings')->nullable();
                $table->text('radiologist_notes')->nullable();
                $table->enum('status', ['ordered', 'captured', 'reported', 'reviewed'])->default('ordered');
                $table->date('study_date')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('imaging_files')) {
            Schema::create('imaging_files', function (Blueprint $table) {
                $table->id();
                $table->foreignId('imaging_study_id')->constrained()->onDelete('cascade');
                $table->string('file_path');
                $table->string('file_name');
                $table->string('mime_type')->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('lab_orders')) {
            Schema::create('lab_orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('clinic_id');
                $table->foreignId('patient_id')->constrained()->onDelete('cascade');
                $table->foreignId('dentist_id')->constrained('users')->onDelete('cascade');
                $table->string('order_number')->unique();
                $table->string('lab_name')->nullable();
                $table->enum('category', ['impression', 'crown', 'bridge', 'denture', 'bleaching_tray', 'night_guard', 'orthodontic', 'other']);
                $table->text('instructions');
                $table->json('materials')->nullable();
                $table->string('shade')->nullable();
                $table->date('sent_date')->nullable();
                $table->date('expected_return_date')->nullable();
                $table->date('received_date')->nullable();
                $table->decimal('lab_cost', 10, 2)->nullable();
                $table->enum('status', ['draft', 'sent', 'in_progress', 'received', 'fitted'])->default('draft');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_orders');
        Schema::dropIfExists('imaging_files');
        Schema::dropIfExists('imaging_studies');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('patient_consents');
        Schema::dropIfExists('consent_templates');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('credit_notes');
        Schema::dropIfExists('chairs');
        Schema::dropIfExists('vendors');
    }
};
