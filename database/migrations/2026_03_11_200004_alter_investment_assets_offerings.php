<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('investment_assets', function (Blueprint $table) {
            $table->enum('offering_type', ['fixed_deposit','mutual_fund','gov_bond','corp_bond','stock','retirement'])->default('stock')->after('asset_type');
            $table->unsignedTinyInteger('risk_score')->default(3)->after('risk_level');
            $table->decimal('base_yield', 8, 4)->default(0)->after('current_price');
            $table->json('tiers')->nullable()->after('base_yield');
            $table->json('maturities')->nullable()->after('tiers');
        });
    }

    public function down(): void
    {
        Schema::table('investment_assets', function (Blueprint $table) {
            $table->dropColumn(['offering_type','risk_score','base_yield','tiers','maturities']);
        });
    }
};

