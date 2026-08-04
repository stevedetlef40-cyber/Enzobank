<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'name' => 'object',
    ];

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}
