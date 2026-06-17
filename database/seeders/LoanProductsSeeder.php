<?php

namespace Database\Seeders;

use App\Models\LoanProduct;
use Illuminate\Database\Seeder;

class LoanProductsSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Personal Loan Classic', 'description' => 'Flexible personal loan', 'interest_rate' => 9.50, 'term_months' => 36, 'min_amount' => 500, 'max_amount' => 10000, 'status' => true],
            ['name' => 'Auto Loan', 'description' => 'Competitive rates for vehicles', 'interest_rate' => 6.25, 'term_months' => 60, 'min_amount' => 5000, 'max_amount' => 50000, 'status' => true],
            ['name' => 'Home Improvement', 'description' => 'For renovation and upgrades', 'interest_rate' => 7.75, 'term_months' => 48, 'min_amount' => 2000, 'max_amount' => 25000, 'status' => true],
        ];
        foreach ($products as $p) {
            LoanProduct::updateOrCreate(
                ['name' => $p['name']],
                [
                    'description'   => $p['description'],
                    'interest_rate' => $p['interest_rate'],
                    'term_months'   => $p['term_months'],
                    'min_amount'    => $p['min_amount'],
                    'max_amount'    => $p['max_amount'],
                    'status'        => $p['status'],
                ]
            );
        }
    }
}

