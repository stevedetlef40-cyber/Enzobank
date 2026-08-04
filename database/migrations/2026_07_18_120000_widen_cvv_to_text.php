<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The CVV is now generated server-side and stored encrypted at rest
     * (~200 chars), which exceeds the previous varchar(191) limit. Widen
     * the column to TEXT so it can hold the ciphertext.
     */
    public function up()
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE strowallet_virtual_cards ALTER COLUMN cvv TYPE TEXT');
        } else {
            DB::statement('ALTER TABLE strowallet_virtual_cards MODIFY cvv TEXT NULL');
        }
    }

    public function down()
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE strowallet_virtual_cards ALTER COLUMN cvv TYPE VARCHAR(191)');
        } else {
            DB::statement('ALTER TABLE strowallet_virtual_cards MODIFY cvv VARCHAR(191) NULL');
        }
    }
};
