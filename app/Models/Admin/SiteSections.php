<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSections extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'value' => 'object',
    ];

    public function scopeSiteCookie($query)
    {
        return $query->where('key', 'site_cookie');
    }

    public function scopeGetData($query, $slug)
    {
        return $this->where('key', $slug);
    }
}
