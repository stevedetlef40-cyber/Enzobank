<?php

namespace App\Models;

use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'principal' => 'decimal:4',
        'interest_rate' => 'decimal:4',
        'term_months' => 'integer',
        'start_date' => 'date',
        'balance_principal' => 'decimal:4',
        'next_due_date' => 'date',
        'accrued_interest' => 'decimal:4',
        'last_accrual_date' => 'date',
        'rate_schedule' => 'array',
        'admin_notes' => 'array',
        'funded_amount' => 'decimal:4',
        'approved_at' => 'datetime',
        'funded_at' => 'datetime',
        'funded_by_admin_id' => 'integer',
        'withdrawal_restricted' => 'boolean',
        'deposit_required_for_withdrawal' => 'boolean',
        'deposit_made_for_withdrawal' => 'decimal:4',
        'origination_fee_percent' => 'decimal:4',
        'origination_fee_amount' => 'decimal:4',
        'service_fee_percent' => 'decimal:4',
        'withdrawal_fee_percent' => 'decimal:4',
    ];

    // Loan Types
    public const TYPE_INVESTMENT = 'investment';

    public const TYPE_PERSONAL = 'personal';

    public const TYPE_BUSINESS = 'business';

    // Approval Statuses
    public const APPROVAL_PENDING_REVIEW = 'pending_review';

    public const APPROVAL_APPROVED = 'approved';

    public const APPROVAL_REJECTED = 'rejected';

    public const APPROVAL_FUNDED = 'funded';

    // Loan Statuses
    public const STATUS_PENDING = 'pending';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_CLOSED = 'closed';

    public const STATUS_DEFAULTED = 'defaulted';

    // Interest Methods
    public const METHOD_SIMPLE = 'simple';

    public const METHOD_COMPOUND = 'compound';

    public const METHOD_AMORTIZED = 'amortized';

    // Payment Frequencies
    public const FREQ_MONTHLY = 'monthly';

    public const FREQ_BIWEEKLY = 'biweekly';

    public const FREQ_WEEKLY = 'weekly';

    // Rate Types
    public const RATE_FIXED = 'fixed';

    public const RATE_VARIABLE = 'variable';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(LoanProduct::class, 'loan_product_id');
    }

    public function investmentPlan(): BelongsTo
    {
        return $this->belongsTo(InvestmentPlan::class, 'investment_plan_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by_admin_id');
    }

    public function fundedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'funded_by_admin_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(LoanPayment::class);
    }

    public function wallet(): HasMany
    {
        return $this->hasMany(LoanWallet::class);
    }

    public function fundings(): HasMany
    {
        return $this->hasMany(LoanFunding::class);
    }

    public function scopeAuth($query)
    {
        return $query->where('user_id', auth()->id());
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePendingReview($query)
    {
        return $query->where('approval_status', self::APPROVAL_PENDING_REVIEW);
    }

    public function isFullyFunded(): bool
    {
        return $this->funded_amount >= $this->principal;
    }

    public function getAvailableForInvestmentAttribute(): float
    {
        $wallet = $this->wallet()->first();

        return $wallet ? (float) $wallet->balance : 0.0;
    }

    public function getTotalEarningsAttribute(): float
    {
        return (float) $this->earnings()->sum('amount');
    }

    public function getWithdrawableEarningsAttribute(): float
    {
        $wallet = $this->wallet()->first();

        return $wallet ? (float) $wallet->earnings_balance : 0.0;
    }

    public function earnings()
    {
        return \App\Models\LoanEarning::whereHas('loanWallet', function ($q) {
            $q->where('loan_id', $this->id);
        });
    }

    public function canWithdrawEarnings(): bool
    {
        if (! $this->deposit_required_for_withdrawal) {
            return true;
        }

        return $this->user->has_made_deposit_for_loan_withdrawal ?? false;
    }

    public function calculateWithdrawalFee(float $amount): float
    {
        $feePercent = (float) ($this->withdrawal_fee_percent ?? 2.5);

        return round($amount * ($feePercent / 100), 4);
    }
}
