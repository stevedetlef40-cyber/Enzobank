<?php

namespace Database\Seeders;

use App\Models\InvestmentAsset;
use Illuminate\Database\Seeder;

class InvestmentOfferingsSeeder extends Seeder
{
    public function run(): void
    {
        $assets = [
            [
                'name'          => 'Fixed Deposit Plus',
                'symbol'        => 'FD-12',
                'asset_type'    => 'cash',
                'offering_type' => 'fixed_deposit',
                'risk_level'    => 'low',
                'risk_score'    => 2,
                'current_price' => 1.000000,
                'base_yield'    => 4.50,
                'tiers'         => [
                    ['min' => 0, 'max' => 5000, 'rate' => 4.25],
                    ['min' => 5000, 'max' => 20000, 'rate' => 5.00],
                    ['min' => 20000, 'max' => null, 'rate' => 5.75],
                ],
                'maturities'    => [6, 12, 24, 36],
            ],
            [
                'name'          => 'Growth Mutual Fund',
                'symbol'        => 'MF-GROWTH',
                'asset_type'    => 'fund',
                'offering_type' => 'mutual_fund',
                'risk_level'    => 'medium',
                'risk_score'    => 4,
                'current_price' => 100.000000,
                'base_yield'    => 7.50,
                'tiers'         => null,
                'maturities'    => [12, 24, 60],
            ],
            [
                'name'          => 'Government Bond 10Y',
                'symbol'        => 'GOV-10Y',
                'asset_type'    => 'bond',
                'offering_type' => 'gov_bond',
                'risk_level'    => 'low',
                'risk_score'    => 1,
                'current_price' => 1000.000000,
                'base_yield'    => 3.60,
                'tiers'         => null,
                'maturities'    => [12, 24, 120],
            ],
            [
                'name'          => 'Corporate Bond AA',
                'symbol'        => 'CORP-AA',
                'asset_type'    => 'bond',
                'offering_type' => 'corp_bond',
                'risk_level'    => 'medium',
                'risk_score'    => 3,
                'current_price' => 1000.000000,
                'base_yield'    => 6.20,
                'tiers'         => [
                    ['min' => 0, 'max' => 10000, 'rate' => 6.00],
                    ['min' => 10000, 'max' => null, 'rate' => 6.40],
                ],
                'maturities'    => [24, 36, 60],
            ],
            [
                'name'          => 'Equity Index ETF',
                'symbol'        => 'STK-ETF',
                'asset_type'    => 'stock',
                'offering_type' => 'stock',
                'risk_level'    => 'high',
                'risk_score'    => 5,
                'current_price' => 50.000000,
                'base_yield'    => 0.00,
                'tiers'         => null,
                'maturities'    => [0],
            ],
            [
                'name'          => 'Retirement Target 2050',
                'symbol'        => 'RET-2050',
                'asset_type'    => 'fund',
                'offering_type' => 'retirement',
                'risk_level'    => 'medium',
                'risk_score'    => 2,
                'current_price' => 25.000000,
                'base_yield'    => 6.00,
                'tiers'         => [
                    ['min' => 0, 'max' => 10000, 'rate' => 5.50],
                    ['min' => 10000, 'max' => null, 'rate' => 6.25],
                ],
                'maturities'    => [36, 60, 120, 240],
            ],
        ];

        foreach ($assets as $a) {
            InvestmentAsset::updateOrCreate(
                ['symbol' => $a['symbol']],
                [
                    'name'          => $a['name'],
                    'asset_type'    => $a['asset_type'],
                    'offering_type' => $a['offering_type'],
                    'risk_level'    => $a['risk_level'],
                    'risk_score'    => $a['risk_score'],
                    'current_price' => $a['current_price'],
                    'base_yield'    => $a['base_yield'],
                    'tiers'         => $a['tiers'],
                    'maturities'    => $a['maturities'],
                    'status'        => true,
                ]
            );
        }
    }
}

