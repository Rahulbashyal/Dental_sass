<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('notifications', 'user_id')) {
            DB::table('notifications')
                ->whereNull('notifiable_id')
                ->whereNotNull('user_id')
                ->update([
                    'notifiable_id' => DB::raw('user_id'),
                    'notifiable_type' => 'App\Models\User'
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
