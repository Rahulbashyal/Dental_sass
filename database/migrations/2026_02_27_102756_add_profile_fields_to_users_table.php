<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('phone');
            $table->string('specialization')->nullable()->after('photo');
            $table->text('bio')->nullable()->after('specialization');
            $table->string('address')->nullable()->after('bio');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('state');
            $table->string('country')->nullable()->default('Nepal')->after('postal_code');
            $table->string('website')->nullable()->after('country');
            $table->json('social_links')->nullable()->after('website');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'photo', 'specialization', 'bio', 'address', 'city', 
                'state', 'postal_code', 'country', 'website', 'social_links'
            ]);
        });
    }
};
