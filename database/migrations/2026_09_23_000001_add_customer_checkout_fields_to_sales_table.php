<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerCheckoutFieldsToSalesTable extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('user_type');
            $table->string('order_status')->default('pending_payment')->after('payment_method');
            $table->string('payment_status')->default('pending')->after('order_status');
            $table->string('paystack_reference')->nullable()->unique()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('paystack_reference');
            $table->text('delivery_address')->nullable()->after('paid_at');
            $table->string('delivery_phone', 30)->nullable()->after('delivery_address');

            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropUnique(['paystack_reference']);
            $table->dropColumn([
                'customer_id',
                'order_status',
                'payment_status',
                'paystack_reference',
                'paid_at',
                'delivery_address',
                'delivery_phone',
            ]);
        });
    }
}