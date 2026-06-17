<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'due_date'    => 'date',
        'amount_due'  => 'decimal:4',
        'amount_paid' => 'decimal:4',
        'principal_due' => 'decimal:4',
        'interest_due'  => 'decimal:4',
        'fee_due'       => 'decimal:4',
        'principal_paid'=> 'decimal:4',
        'interest_paid' => 'decimal:4',
        'fee_paid'      => 'decimal:4',
        'remaining_principal' => 'decimal:4',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
