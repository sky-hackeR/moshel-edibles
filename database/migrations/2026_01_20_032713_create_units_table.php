<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();

            $table->string('name');            // Gram, Kilogram, Tablespoon
            $table->string('symbol');          // g, kg, tbsp
            $table->enum('unit_type', [
                'mass',     // g, kg, oz
                'volume',   // ml, L, tbsp, cup
                'count'     // piece
            ]);

            $table->string('base_unit');       // g, ml, piece

            $table->boolean('is_active')->default(true);

            $table->boolean('use_for_purchase')->default(true);
            $table->boolean('use_for_recipe')->default(true);

            $table->timestamps();

            $table->unique(['symbol', 'unit_type']);
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
}

