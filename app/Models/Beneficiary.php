<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Beneficiary extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'transaction_method_id' => 'integer',
        'user_id' => 'integer',
        'slug' => 'string',
        'info' => 'object',
    ];

    public function method()
    {
        return $this->belongsTo(TransactionMethod::class, 'transaction_method_id', 'id');
    }

    public function scopeAuth($query)
    {
        $query->where('user_id', Auth::guard(get_auth_guard())->user()->id);
    }

    public function scopeSearch($query, $text)
    {
        $query->whereJsonContains('info->account_number', $text)->orWhereJsonContains('info->account_holder_name', $text)->orWhereJsonContains('info->receiver_username', $text);
    }
}
