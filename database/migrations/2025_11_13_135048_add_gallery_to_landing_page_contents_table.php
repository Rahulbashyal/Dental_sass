<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('landing_page_contents', function (Blueprint $table) {
            if (!Schema::hasColumn('landing_page_contents', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('about_image');
            }
        });
    }

    public function down()
    {
        Schema::table('landing_page_contents', function (Blueprint $table) {
            $table->dropColumn('gallery_images');
        });
    }
};