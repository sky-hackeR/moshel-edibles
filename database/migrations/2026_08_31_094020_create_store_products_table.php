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
        Schema::create('store_products', function (Blueprint $table) {
            $table->id();

            /*
             * Links the storefront representation to the ERP product.
             *
             * A product can have only one storefront configuration.
             */
            $table->foreignId('product_id')
                ->unique()
                ->constrained('products')
                ->restrictOnDelete();

            /*
             * Storefront-only content.
             */
            $table->string('short_description')->nullable();
            $table->longText('description')->nullable();

            /*
             * Controls visibility on the public storefront.
             */
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_products');
    }
};