<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_payments', function (Blueprint $table) {
            $table->unsignedInteger('period_number')->nullable()->after('id');
            $table->decimal('principal_due', 19, 4)->default(0)->after('amount_due');
            $table->decimal('interest_due', 19, 4)->default(0)->after('principal_due');
            $table->decimal('fee_due', 19, 4)->default(0)->after('interest_due');
            $table->decimal('principal_paid', 19, 4)->default(0)->after('amount_paid');
            $table->decimal('interest_paid', 19, 4)->default(0)->after('principal_paid');
            $table->decimal('fee_paid', 19, 4)->default(0)->after('interest_paid');
            $table->decimal('remaining_principal', 19, 4)->default(0)->after('fee_paid');
        });
    }

    public function down(): void
    {
        Schema::table('loan_payments', function (Blueprint $table) {
            $table->dropColumn([
                'period_number',
                'principal_due',
                'interest_due',
                'fee_due',
                'principal_paid',
                'interest_paid',
                'fee_paid',
                'remaining_principal',
            ]);
        });
    }
};

