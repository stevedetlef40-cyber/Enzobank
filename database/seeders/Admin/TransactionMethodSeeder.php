<?php

namespace Database\Seeders\Admin;

use App\Constants\GlobalConst;
use App\Models\TransactionMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $methods = [
            GlobalConst::TRX_OWN_BANK_TRANSFER => Str::slug(GlobalConst::TRX_OWN_BANK_TRANSFER),
            GlobalConst::TRX_OTHER_BANK_TRANSFER => Str::slug(GlobalConst::TRX_OTHER_BANK_TRANSFER),
        ];

        $data = [];

        foreach ($methods as $name => $slug) {
            $data[] = [
                'name' => $name,
                'slug' => $slug,
                'last_edit_by' => 1,
                'status' => true,
                'created_at' => now(),
            ];
        }

        TransactionMethod::insert($data);

    }
}
