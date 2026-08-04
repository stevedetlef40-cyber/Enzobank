<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanProduct extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'interest_rate' => 'decimal:4',
        'term_months' => 'integer',
        'min_amount' => 'decimal:4',
        'max_amount' => 'decimal:4',
        'status' => 'boolean',
        'origination_fee_percent' => 'decimal:4',
        'service_fee_percent' => 'decimal:4',
        'withdrawal_fee_percent' => 'decimal:4',
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function investmentPlan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InvestmentPlan::class, 'investment_plan_id');
    }
}
