<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-user switch that controls whether a purchased $10 virtual card is
     * required before a user can send an international transfer or make a
     * withdrawal. Admins can disable this for individual users so they are not
     * forced to buy a card. Defaults to true to preserve existing behaviour.
     */
    public function up()
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users ADD COLUMN IF NOT EXISTS card_required BOOLEAN NOT NULL DEFAULT TRUE');
        } else {
            if (! Schema::hasColumn('users', 'card_required')) {
                DB::statement('ALTER TABLE users ADD COLUMN card_required BOOLEAN NOT NULL DEFAULT TRUE');
            }
        }
    }

    public function down()
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users DROP COLUMN IF EXISTS card_required');
        } else {
            if (Schema::hasColumn('users', 'card_required')) {
                DB::statement('ALTER TABLE users DROP COLUMN card_required');
            }
        }
    }
};
