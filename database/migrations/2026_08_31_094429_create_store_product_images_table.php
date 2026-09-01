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
        Schema::create('store_product_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_product_id')
                ->constrained('store_products')
                ->cascadeOnDelete();

            /*
             * Path to the stored image.
             *
             * Example:
             * store/products/chocolate-cake/main.jpg
             */
            $table->string('path');

            /*
             * Accessibility / SEO text.
             */
            $table->string('alt_text')->nullable();

            /*
             * Controls image ordering on the storefront.
             */
            $table->unsignedInteger('sort_order')->default(0);

            /*
             * Marks the primary product image.
             */
            $table->boolean('is_primary')->default(false);

            $table->timestamps();

            /*
             * Useful for quickly retrieving images
             * in their intended display order.
             */
            $table->index(['store_product_id', 'sort_order']);
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_product_images');
    }
};