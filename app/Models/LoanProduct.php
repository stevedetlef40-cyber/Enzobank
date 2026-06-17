<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanProduct extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'name'          => 'string',
        'interest_rate' => 'decimal:4',
        'term_months'   => 'integer',
        'min_amount'    => 'decimal:4',
        'max_amount'    => 'decimal:4',
        'status'        => 'boolean',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}

