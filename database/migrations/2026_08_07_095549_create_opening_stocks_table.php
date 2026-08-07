<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('opening_stocks', function (Blueprint $table) {
            $table->id();

            $table->string('item_type');

            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->restrictOnDelete();

            $table->foreignId('ingredient_id')
                ->nullable()
                ->constrained('ingredients')
                ->restrictOnDelete();

            $table->decimal('quantity', 15, 4);

            $table->decimal('average_cost', 15, 6)
                ->nullable();

            $table->text('reason')
                ->nullable();

            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('admins')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('item_type');
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('opening_stocks');
    }
};