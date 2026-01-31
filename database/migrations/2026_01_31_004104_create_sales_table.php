<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique(); 
            
            // Flexible user tracking
            $table->unsignedBigInteger('user_id'); 
            $table->string('user_type');
            
            $table->decimal('total_amount', 15, 2); 
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('payable_amount', 15, 2);
            $table->string('payment_method');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('sales');
    }
}
