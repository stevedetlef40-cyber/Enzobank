<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->enum('interest_method', ['simple','compound','amortized'])->default('amortized')->after('interest_rate');
            $table->enum('payment_frequency', ['monthly','biweekly','weekly'])->default('monthly')->after('term_months');
            $table->enum('rate_type', ['fixed','variable'])->default('fixed')->after('interest_method');
            $table->json('rate_schedule')->nullable()->after('rate_type');
            $table->unsignedSmallInteger('grace_days')->default(0)->after('start_date');
            $table->enum('late_fee_type', ['percent','flat'])->default('percent')->after('grace_days');
            $table->decimal('late_fee_value', 10, 4)->default(0)->after('late_fee_type');
            $table->decimal('early_settlement_fee_percent', 10, 4)->default(0)->after('late_fee_value');
            $table->decimal('accrued_interest', 19, 4)->default(0)->after('balance_principal');
            $table->date('last_accrual_date')->nullable()->after('accrued_interest');
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'interest_method',
                'payment_frequency',
                'rate_type',
                'rate_schedule',
                'grace_days',
                'late_fee_type',
                'late_fee_value',
                'early_settlement_fee_percent',
                'accrued_interest',
                'last_accrual_date',
            ]);
        });
    }
};

