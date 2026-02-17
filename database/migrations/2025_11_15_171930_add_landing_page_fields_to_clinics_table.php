<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'theme_color')) {
                $table->string('theme_color')->default('#2563eb')->after('country');
            }
            if (!Schema::hasColumn('clinics', 'accent_color')) {
                $table->string('accent_color')->default('#3b82f6')->after('primary_color');
            }
            if (!Schema::hasColumn('clinics', 'tagline')) {
                $table->string('tagline')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('clinics', 'description')) {
                $table->text('description')->nullable()->after('tagline');
            }
            if (!Schema::hasColumn('clinics', 'about')) {
                $table->text('about')->nullable()->after('description');
            }
            if (!Schema::hasColumn('clinics', 'services')) {
                $table->json('services')->nullable()->after('about');
            }
            if (!Schema::hasColumn('clinics', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('services');
            }
            if (!Schema::hasColumn('clinics', 'facebook_url')) {
                $table->string('facebook_url')->nullable()->after('gallery_images');
            }
            if (!Schema::hasColumn('clinics', 'instagram_url')) {
                $table->string('instagram_url')->nullable()->after('facebook_url');
            }
            if (!Schema::hasColumn('clinics', 'website_url')) {
                $table->string('website_url')->nullable()->after('instagram_url');
            }
        });
    }

    public function down()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $columns = ['theme_color', 'accent_color', 'tagline', 'description', 'about', 'services', 'gallery_images', 'facebook_url', 'instagram_url', 'website_url'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('clinics', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};