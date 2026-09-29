<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaystackVerificationDetailsToSalesTable extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('paystack_amount')->nullable()->after('paystack_reference');
            $table->char('paystack_currency', 3)->nullable()->after('paystack_amount');
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['paystack_amount', 'paystack_currency']);
        });
    }
}