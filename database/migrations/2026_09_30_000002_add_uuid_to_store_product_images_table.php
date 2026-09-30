<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddUuidToStoreProductImagesTable extends Migration
{
    public function up()
    {
        Schema::table('store_product_images', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique();
        });

        DB::table('store_product_images')->select('id')->orderBy('id')->get()->each(function ($image) {
            DB::table('store_product_images')->where('id', $image->id)->update([
                'uuid' => (string) Str::uuid(),
            ]);
        });
    }

    public function down()
    {
        Schema::table('store_product_images', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
}