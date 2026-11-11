<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditSessionsAndLogsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('session_id', 191)->index();
            $table->string('user_type', 30)->index(); // 'admin', 'staff', 'customer'
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('user_name', 150)->nullable();
            $table->string('user_email', 150)->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device', 100)->nullable();
            $table->dateTime('started_at')->index();
            $table->dateTime('last_activity_at')->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('audit_session_id')->index();
            $table->string('user_type', 30)->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('category', 60)->index(); // Auth, POS, Inventory, Production, Store, etc.
            $table->string('action', 120)->index();
            $table->text('description')->nullable();
            $table->string('method', 10)->default('GET');
            $table->text('url')->nullable();
            $table->string('route_name', 120)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->json('details')->nullable();
            $table->timestamp('created_at')->nullable()->index();

            $table->foreign('audit_session_id')
                ->references('id')
                ->on('audit_sessions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('audit_sessions');
    }
}
