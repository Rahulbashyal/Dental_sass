<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('treatment_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('treatment_plans', 'estimated_cost')) {
                $table->decimal('estimated_cost', 10, 2)->nullable()->after('description');
            }
            if (!Schema::hasColumn('treatment_plans', 'estimated_duration')) {
                $table->string('estimated_duration')->nullable()->after('estimated_cost');
            }
            if (!Schema::hasColumn('treatment_plans', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('estimated_duration');
            }
        });
    }

    public function down()
    {
        Schema::table('treatment_plans', function (Blueprint $table) {
            $columns = ['estimated_cost', 'estimated_duration', 'priority'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('treatment_plans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};