<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();

            // Production Volume
            $table->decimal('quantity', 15, 2); 
            
            // Financials
            $table->decimal('unit_cost', 12, 2); 
            $table->decimal('total_cost', 12, 2);
            $table->decimal('selling_price', 12, 2);
            $table->decimal('expected_revenue', 12, 2);
            $table->decimal('profit', 12, 2);
            
            // Metadata
            $table->text('notes')->nullable();
            $table->timestamp('produced_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productions');
    }
}