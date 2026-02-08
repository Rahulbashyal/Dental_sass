<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('subscription_plans')) {
            Schema::create('subscription_plans', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description');
                $table->decimal('price', 10, 2);
                $table->enum('billing_cycle', ['monthly', 'yearly']);
                $table->json('features');
                $table->integer('max_users')->default(5);
                $table->integer('max_patients')->default(100);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
                $table->foreignId('subscription_plan_id')->constrained()->onDelete('cascade');
                $table->enum('status', ['active', 'cancelled', 'expired', 'trial'])->default('trial');
                $table->date('starts_at');
                $table->date('ends_at');
                $table->date('trial_ends_at')->nullable();
                $table->decimal('amount', 10, 2);
                $table->string('stripe_subscription_id')->nullable();
                $table->timestamps();
            });
        }

        // Note: invoices table is created in a separate migration
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};