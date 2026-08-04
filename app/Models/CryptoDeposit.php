<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoDeposit extends Model
{
    protected $table = 'crypto_deposits';

    protected $fillable = [
        'user_id',
        'coin_symbol',
        'network',
        'wallet_address',
        'amount_usd',
        'amount_crypto',
        'tx_hash',
        'proof',
        'status',
        'admin_note',
        'qualifies_for_unlock',
        'confirmed_at',
    ];

    protected $casts = [
        'amount_usd' => 'decimal:2',
        'amount_crypto' => 'decimal:8',
        'confirmed_at' => 'datetime',
        'qualifies_for_unlock' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAuth($query)
    {
        return $query->where('user_id', auth()->id());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
