<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Strowallet API returns card_user_id and card_created_date as null in
     * certain environments (e.g. test/local mode), violating the NOT NULL
     * constraint. Make both columns nullable.
     */
    public function up()
    {
        if (Schema::hasTable('strowallet_virtual_cards')) {
            DB::statement('ALTER TABLE strowallet_virtual_cards ALTER COLUMN card_user_id DROP NOT NULL');
            DB::statement('ALTER TABLE strowallet_virtual_cards ALTER COLUMN card_created_date DROP NOT NULL');
        }
    }

    public function down()
    {
        if (Schema::hasTable('strowallet_virtual_cards')) {
            DB::statement('ALTER TABLE strowallet_virtual_cards ALTER COLUMN card_user_id SET NOT NULL');
            DB::statement('ALTER TABLE strowallet_virtual_cards ALTER COLUMN card_created_date SET NOT NULL');
        }
    }
};
