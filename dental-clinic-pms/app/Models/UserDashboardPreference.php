<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDashboardPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'widget_key',
        'x_pos',
        'y_pos',
        'width',
        'height',
        'is_visible',
        'quick_actions',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}