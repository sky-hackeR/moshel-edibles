<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class WidenQueueAttemptsColumn extends Migration
{
    public function up()
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE jobs MODIFY attempts INT UNSIGNED NOT NULL DEFAULT 0');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE jobs ALTER COLUMN attempts TYPE INTEGER USING attempts::integer');
        } elseif ($driver === 'sqlsrv') {
            DB::statement('ALTER TABLE jobs ALTER COLUMN attempts INT NOT NULL');
        }
    }

    public function down()
    {
        $driver = DB::connection()->getDriverName();
        if (!in_array($driver, ['mysql', 'pgsql', 'sqlsrv'], true)) {
            return;
        }

        if ((int) DB::table('jobs')->max('attempts') > 255) {
            throw new RuntimeException('Queue attempts exceed the previous column capacity.');
        }

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE jobs MODIFY attempts TINYINT UNSIGNED NOT NULL DEFAULT 0');
        } elseif ($driver === 'sqlsrv') {
            DB::statement('ALTER TABLE jobs ALTER COLUMN attempts TINYINT NOT NULL');
        } else {
            DB::statement('ALTER TABLE jobs ALTER COLUMN attempts TYPE SMALLINT USING attempts::smallint');
        }
    }
}