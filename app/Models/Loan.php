<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'principal'         => 'decimal:4',
        'interest_rate'     => 'decimal:4',
        'term_months'       => 'integer',
        'start_date'        => 'date',
        'balance_principal' => 'decimal:4',
        'next_due_date'     => 'date',
        'accrued_interest'  => 'decimal:4',
        'last_accrual_date' => 'date',
        'rate_schedule'     => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(LoanProduct::class, 'loan_product_id');
    }

    public function payments()
    {
        return $this->hasMany(LoanPayment::class);
    }
}
