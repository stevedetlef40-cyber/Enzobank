<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanEarning extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:4',
    ];

    public const TYPE_ROI = 'roi';

    public const TYPE_DIVIDEND = 'dividend';

    public const TYPE_INTEREST = 'interest';

    public const TYPE_CAPITAL_GAIN = 'capital_gain';

    public const STATUS_PENDING = 'pending';

    public const STATUS_CREDITED = 'credited';

    public const STATUS_WITHDRAWN = 'withdrawn';

    public function loanWallet(): BelongsTo
    {
        return $this->belongsTo(LoanWallet::class, 'loan_wallet_id');
    }

    public function userInvestment(): BelongsTo
    {
        return $this->belongsTo(UserInvestment::class, 'user_investment_id');
    }

    public function withdrawalTransaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'withdrawal_transaction_id');
    }

    public function scopeCredited($query)
    {
        return $query->where('status', self::STATUS_CREDITED);
    }

    public function scopeWithdrawable($query)
    {
        return $query->where('status', self::STATUS_CREDITED);
    }
}
