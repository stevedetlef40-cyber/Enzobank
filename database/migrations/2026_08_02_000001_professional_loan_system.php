<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add professional loan fields to loans table
        Schema::table('loans', function (Blueprint $table) {
            // Loan type: investment_loan, personal_loan, etc.
            $table->enum('loan_type', ['investment', 'personal', 'business'])->default('investment')->after('currency');

            // Admin approval workflow
            $table->enum('approval_status', ['pending_review', 'approved', 'rejected', 'funded'])->default('pending_review')->after('status');
            $table->foreignId('approved_by_admin_id')->nullable()->constrained('admins')->nullOnDelete()->after('approval_status');
            $table->timestamp('approved_at')->nullable()->after('approved_by_admin_id');
            $table->text('rejection_reason')->nullable()->after('approved_at');

            // Funding (admin releases funds)
            $table->decimal('funded_amount', 19, 4)->default(0)->after('principal');
            $table->timestamp('funded_at')->nullable()->after('funded_amount');
            $table->foreignId('funded_by_admin_id')->nullable()->constrained('admins')->nullOnDelete()->after('funded_at');

            // Restrictions
            $table->boolean('withdrawal_restricted')->default(true)->after('funded_by_admin_id'); // Cannot withdraw principal
            $table->boolean('deposit_required_for_withdrawal')->default(true)->after('withdrawal_restricted');
            $table->decimal('deposit_made_for_withdrawal', 19, 4)->default(0)->after('deposit_required_for_withdrawal');

            // Fees
            $table->decimal('origination_fee_percent', 8, 4)->default(0)->after('early_settlement_fee_percent');
            $table->decimal('origination_fee_amount', 19, 4)->default(0)->after('origination_fee_percent');
            $table->decimal('service_fee_percent', 8, 4)->default(0)->after('origination_fee_amount');
            $table->decimal('withdrawal_fee_percent', 8, 4)->default(2.5)->after('service_fee_percent'); // Fee on earnings withdrawal

            // Investment linkage
            $table->foreignId('investment_plan_id')->nullable()->constrained('investment_plans')->nullOnDelete()->after('loan_product_id');

            // Metadata
            $table->json('admin_notes')->nullable()->after('rate_schedule');
        });

        // Create loan_wallets table - restricted wallet for loan funds
        Schema::create('loan_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained('currencies')->cascadeOnDelete();
            $table->decimal('balance', 19, 4)->default(0); // Available for investment
            $table->decimal('invested_amount', 19, 4)->default(0); // Amount currently invested
            $table->decimal('earnings_balance', 19, 4)->default(0); // Available earnings for withdrawal
            $table->decimal('withdrawn_earnings', 19, 4)->default(0); // Total earnings withdrawn
            $table->enum('status', ['active', 'frozen', 'closed'])->default('active');
            $table->timestamps();
            $table->index(['loan_id', 'user_id']);
        });

        // Create loan_fundings table - track admin funding actions
        Schema::create('loan_fundings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->decimal('amount', 19, 4);
            $table->decimal('fee_deducted', 19, 4)->default(0);
            $table->decimal('net_amount', 19, 4);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
            $table->timestamps();
        });

        // Create loan_earnings table - track investment earnings from loan funds
        Schema::create('loan_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_wallet_id')->constrained('loan_wallets')->cascadeOnDelete();
            $table->foreignId('user_investment_id')->constrained('user_investments')->cascadeOnDelete();
            $table->decimal('amount', 19, 4);
            $table->enum('type', ['roi', 'dividend', 'interest', 'capital_gain'])->default('roi');
            $table->enum('status', ['pending', 'credited', 'withdrawn'])->default('pending');
            $table->timestamp('credited_at')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            $table->foreignId('withdrawal_transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->timestamps();
        });

        // Add loan_wallet_id to user_investments for tracking
        Schema::table('user_investments', function (Blueprint $table) {
            $table->foreignId('loan_wallet_id')->nullable()->constrained('loan_wallets')->nullOnDelete()->after('plan_id');
            $table->boolean('funded_by_loan')->default(false)->after('loan_wallet_id');
        });

        // Add has_made_deposit_for_loan_withdrawal to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('has_made_deposit_for_loan_withdrawal')->default(false)->after('status');
            $table->timestamp('first_deposit_for_loan_withdrawal_at')->nullable()->after('has_made_deposit_for_loan_withdrawal');
        });

        // Add deposit_for_loan_withdrawal tracking to transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('is_deposit_for_loan_withdrawal')->default(false)->after('remark');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['is_deposit_for_loan_withdrawal']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['has_made_deposit_for_loan_withdrawal', 'first_deposit_for_loan_withdrawal_at']);
        });

        Schema::table('user_investments', function (Blueprint $table) {
            $table->dropColumn(['loan_wallet_id', 'funded_by_loan']);
        });

        Schema::dropIfExists('loan_earnings');
        Schema::dropIfExists('loan_fundings');
        Schema::dropIfExists('loan_wallets');

        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'loan_type',
                'approval_status',
                'approved_by_admin_id',
                'approved_at',
                'rejection_reason',
                'funded_amount',
                'funded_at',
                'funded_by_admin_id',
                'withdrawal_restricted',
                'deposit_required_for_withdrawal',
                'deposit_made_for_withdrawal',
                'origination_fee_percent',
                'origination_fee_amount',
                'service_fee_percent',
                'withdrawal_fee_percent',
                'investment_plan_id',
                'admin_notes',
            ]);
        });
    }
};
