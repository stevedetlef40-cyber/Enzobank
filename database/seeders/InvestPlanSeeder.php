<?php

namespace Database\Seeders;

use App\Models\CryptoWallet;
use App\Models\InvestmentPlan;
use Illuminate\Database\Seeder;

class InvestPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            ['name' => 'Basic Deluxe',   'min_amount' => 50,     'max_amount' => 999,    'roi_percent' => 15,  'duration_days' => 30],
            ['name' => 'Promo Package',  'min_amount' => 200,    'max_amount' => 10000,  'roi_percent' => 25,  'duration_days' => 60],
            ['name' => 'Elite Deluxe',   'min_amount' => 1000,   'max_amount' => 4999,   'roi_percent' => 35,  'duration_days' => 90],
            ['name' => 'Pro Deluxe',     'min_amount' => 5000,   'max_amount' => 29999,  'roi_percent' => 45,  'duration_days' => 120],
            ['name' => 'Contract I',     'min_amount' => 30000,  'max_amount' => 45999,  'roi_percent' => 55,  'duration_days' => 180],
            ['name' => 'Contract II',    'min_amount' => 46000,  'max_amount' => 78999,  'roi_percent' => 65,  'duration_days' => 270],
            ['name' => 'Contract III',   'min_amount' => 79000,  'max_amount' => null,   'roi_percent' => 80,  'duration_days' => 365],
        ];

        foreach ($plans as $p) {
            InvestmentPlan::updateOrCreate(['name' => $p['name']], $p);
        }

        $wallets = [
            ['coin_name' => 'Bitcoin',       'symbol' => 'BTC',          'network' => 'BTC',     'wallet_address' => 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh'],
            ['coin_name' => 'Ethereum',      'symbol' => 'ETH',          'network' => 'ERC20',   'wallet_address' => '0x742d35Cc6634C0532925a3b844Bc9e7595f2bD18'],
            ['coin_name' => 'USDT',          'symbol' => 'USDT',         'network' => 'TRC20',   'wallet_address' => 'TXYZ1234567890abcdefghijklmnopqrstuvwxyz'],
            ['coin_name' => 'USDT',          'symbol' => 'USDT',         'network' => 'ERC20',   'wallet_address' => '0x1234567890abcdef1234567890abcdef12345678'],
            ['coin_name' => 'USDT',          'symbol' => 'USDT',         'network' => 'BEP20',   'wallet_address' => '0xabcdef1234567890abcdef1234567890abcdef12'],
            ['coin_name' => 'Tron',          'symbol' => 'TRX',          'network' => 'TRX',     'wallet_address' => 'TXYZ9876543210abcdefghijklmnopqrstuvwxyz'],
        ];

        foreach ($wallets as $w) {
            CryptoWallet::updateOrCreate(
                ['coin_name' => $w['coin_name'], 'network' => $w['network']],
                $w
            );
        }
    }
}
