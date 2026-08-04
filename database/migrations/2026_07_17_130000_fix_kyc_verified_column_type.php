<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * kyc_verified stores a 4-state status (0 default, 1 approved, 2 pending, 3 rejected)
     * but was declared as boolean, which breaks on PostgreSQL where boolean != integer.
     * Convert it to a small integer so the existing KYC approve/reject logic works.
     */
    public function up()
    {
        if (Schema::hasColumn('users', 'kyc_verified')) {
            $type = DB::selectOne("select data_type from information_schema.columns where table_name='users' and column_name='kyc_verified'");
            if ($type && $type->data_type === 'boolean') {
                DB::statement('ALTER TABLE users ALTER COLUMN kyc_verified DROP DEFAULT, ALTER COLUMN kyc_verified TYPE smallint USING kyc_verified::int, ALTER COLUMN kyc_verified SET DEFAULT 0');
            }
        }
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'kyc_verified')) {
            $type = DB::selectOne("select data_type from information_schema.columns where table_name='users' and column_name='kyc_verified'");
            if ($type && $type->data_type === 'smallint') {
                DB::statement('ALTER TABLE users ALTER COLUMN kyc_verified TYPE boolean USING kyc_verified::boolean');
            }
        }
    }
};
