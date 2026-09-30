<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingAndInventoryReconciliationToSalesTable extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('shipping_fee', 12, 2)->default(0)->after('total_amount');
            $table->timestamp('inventory_deducted_at')->nullable()->after('paid_at');
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['shipping_fee', 'inventory_deducted_at']);
        });
    }
}