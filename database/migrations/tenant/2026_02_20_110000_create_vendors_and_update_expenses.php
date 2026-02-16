<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vendors')) {
            Schema::create('vendors', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('clinic_id');
                $table->string('name');
                $table->string('contact_person')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('category')->nullable(); // Dental Supplies, Equipment, Utilities, etc.
                $table->timestamps();
            });
        }

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

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vendor_id');
            $table->dropColumn(['reference_number', 'status']);
        });
        Schema::dropIfExists('vendors');
    }
};
