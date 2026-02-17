<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('provisioning_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tenant_id')->index();
            $table->string('level')->default('info');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('provisioning_logs');
    }
};
