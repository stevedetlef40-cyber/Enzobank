<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvestment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:8',
        'expected_return' => 'decimal:8',
        'maturity_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(InvestmentPlan::class, 'plan_id');
    }

    public function earnings()
    {
        return $this->hasMany(EarningsLog::class, 'investment_id');
    }

    public function scopeAuth($query)
    {
        return $query->where('user_id', auth()->id());
    }
}
