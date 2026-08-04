<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanWallet extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'balance' => 'decimal:4',
        'invested_amount' => 'decimal:4',
        'earnings_balance' => 'decimal:4',
        'withdrawn_earnings' => 'decimal:4',
    ];

    public const STATUS_ACTIVE = 'active';

    public const STATUS_FROZEN = 'frozen';

    public const STATUS_CLOSED = 'closed';

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Admin\Currency::class);
    }

    public function investments(): HasMany
    {
        return $this->hasMany(UserInvestment::class, 'loan_wallet_id');
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(LoanEarning::class, 'loan_wallet_id');
    }

    public function fundings(): HasMany
    {
        return $this->hasMany(LoanFunding::class, 'loan_id', 'loan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function getAvailableForInvestmentAttribute(): float
    {
        return (float) $this->balance;
    }

    public function canInvest(float $amount): bool
    {
        return $this->status === self::STATUS_ACTIVE && $this->balance >= $amount;
    }

    public function addEarnings(float $amount): void
    {
        $this->increment('earnings_balance', $amount);
    }

    public function withdrawEarnings(float $amount): bool
    {
        if ($this->earnings_balance < $amount) {
            return false;
        }
        $this->decrement('earnings_balance', $amount);
        $this->increment('withdrawn_earnings', $amount);

        return true;
    }

    public function invest(float $amount): bool
    {
        if (! $this->canInvest($amount)) {
            return false;
        }
        $this->decrement('balance', $amount);
        $this->increment('invested_amount', $amount);

        return true;
    }

    public function releaseInvestment(float $amount): void
    {
        $this->increment('balance', $amount);
        $this->decrement('invested_amount', $amount);
    }
}
