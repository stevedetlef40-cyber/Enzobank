<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaysSeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            ['holiday_date' => '2026-01-01', 'name' => 'New Year\'s Day', 'region' => null],
            ['holiday_date' => '2026-12-25', 'name' => 'Christmas Day', 'region' => null],
            ['holiday_date' => '2026-07-04', 'name' => 'Independence Day', 'region' => 'US'],
        ];
        foreach ($holidays as $h) {
            Holiday::updateOrCreate(
                ['holiday_date' => $h['holiday_date'], 'region' => $h['region']],
                ['name' => $h['name']]
            );
        }
    }
}

