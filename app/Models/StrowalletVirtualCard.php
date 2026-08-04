<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrowalletVirtualCard extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'name_on_card' => 'string',
        'card_id' => 'string',
        'card_created_date' => 'string',
        'card_type' => 'string',
        'card_brand' => 'string',
        'card_user_id' => 'string',
        'reference' => 'string',
        'card_status' => 'string',
        'customer_id' => 'string',
        'card_name' => 'string',
        'card_number' => 'string',
        'last4' => 'string',
        'cvv' => 'string',
        'expiry' => 'string',
        'customer_email' => 'string',
        'balance' => 'string',
        'status' => 'boolean',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAuth($query)
    {
        $query->where('user_id', auth()->user()->id);
    }

    /**
     * Parse the stored expiry (e.g. "12/28", "12/2028", "1228") into a month.
     */
    public function getExpiryMonthAttribute(): string
    {
        return $this->expiryMonthPart();
    }

    /**
     * Parse the stored expiry (e.g. "12/28", "12/2028", "1228") into a year.
     */
    public function getExpiryYearAttribute(): string
    {
        return $this->expiryYearPart();
    }

    private function expiryMonthPart(): string
    {
        $expiry = trim((string) $this->expiry);
        if ($expiry === '') {
            return '';
        }
        $expiry = preg_replace('/\s+/', '', $expiry);
        if (str_contains($expiry, '/')) {
            $parts = explode('/', $expiry);
            $month = (int) ($parts[0] ?? 0);

            return $month > 0 ? str_pad((string) $month, 2, '0', STR_PAD_LEFT) : '';
        }

        return substr($expiry, 0, 2);
    }

    private function expiryYearPart(): string
    {
        $expiry = trim((string) $this->expiry);
        if ($expiry === '') {
            return '';
        }
        $expiry = preg_replace('/\s+/', '', $expiry);
        if (str_contains($expiry, '/')) {
            $parts = explode('/', $expiry);
            $year = trim($parts[1] ?? '');

            return strlen($year) === 4 ? substr($year, 2) : $year;
        }

        return substr($expiry, 2, 2);
    }
}
