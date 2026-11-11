<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddSlugStatusAndEmailVerificationToCustomersTable extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique();
            $table->string('status')->default('inactive');
            $table->timestamp('email_verified_at')->nullable();
        });

        DB::table('customers')->select('id', 'name')->orderBy('id')->get()->each(function ($customer) {
            $baseSlug = Str::slug($customer->name) ?: 'customer';
            $slug = $baseSlug;

            while (DB::table('customers')->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . Str::lower(Str::random(8));
            }

            DB::table('customers')->where('id', $customer->id)->update(['slug' => $slug]);
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'status', 'email_verified_at']);
        });
    }
}