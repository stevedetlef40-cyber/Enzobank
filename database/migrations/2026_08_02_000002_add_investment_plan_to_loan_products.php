<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_products', function (Blueprint $table) {
            $table->foreignId('investment_plan_id')->nullable()->constrained('investment_plans')->nullOnDelete()->after('description');
            $table->enum('loan_type', ['investment', 'personal', 'business'])->default('investment')->after('investment_plan_id');
            $table->decimal('origination_fee_percent', 8, 4)->default(1.0)->after('loan_type');
            $table->decimal('service_fee_percent', 8, 4)->default(0.5)->after('origination_fee_percent');
            $table->decimal('withdrawal_fee_percent', 8, 4)->default(2.5)->after('service_fee_percent');
            $table->boolean('withdrawal_restricted')->default(true)->after('withdrawal_fee_percent');
            $table->boolean('deposit_required_for_withdrawal')->default(true)->after('withdrawal_restricted');
        });
    }

    public function down(): void
    {
        Schema::table('loan_products', function (Blueprint $table) {
            $table->dropColumn([
                'investment_plan_id',
                'loan_type',
                'origination_fee_percent',
                'service_fee_percent',
                'withdrawal_fee_percent',
                'withdrawal_restricted',
                'deposit_required_for_withdrawal',
            ]);
        });
    }
};
