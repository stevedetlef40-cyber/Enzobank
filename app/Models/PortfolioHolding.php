<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioHolding extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'quantity' => 'decimal:10',
        'avg_cost' => 'decimal:6',
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function asset()
    {
        return $this->belongsTo(InvestmentAsset::class, 'investment_asset_id');
    }
}

