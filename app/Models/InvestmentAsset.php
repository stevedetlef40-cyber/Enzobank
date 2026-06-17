<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentAsset extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'name'          => 'string',
        'symbol'        => 'string',
        'asset_type'    => 'string',
        'risk_level'    => 'string',
        'current_price' => 'decimal:6',
        'status'        => 'boolean',
        'offering_type' => 'string',
        'risk_score'    => 'integer',
        'base_yield'    => 'decimal:4',
        'tiers'         => 'array',
        'maturities'    => 'array',
    ];

    public function holdings()
    {
        return $this->hasMany(PortfolioHolding::class, 'investment_asset_id');
    }
}
