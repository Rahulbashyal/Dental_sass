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
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'notifiable_id') && Schema::hasColumn('notifications', 'notifiable_type')) {
                $table->index(['notifiable_id', 'notifiable_type'], 'notifications_notifiable_index');
            }
            if (Schema::hasColumn('notifications', 'read_at')) {
                $table->index('read_at');
            }
            if (Schema::hasColumn('notifications', 'user_id')) {
                $table->index('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_notifiable_index');
            $table->dropIndex(['read_at']);
            $table->dropIndex(['user_id']);
        });
    }
};
