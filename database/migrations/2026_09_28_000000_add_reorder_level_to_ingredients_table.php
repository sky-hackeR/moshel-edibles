<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReorderLevelToIngredientsTable extends Migration
{
    public function up()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->decimal('reorder_level', 12, 3)->nullable()->after('base_unit_id');
        });
    }

    public function down()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn('reorder_level');
        });
    }
}