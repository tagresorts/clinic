<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultDashboardLayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'widget_key',
        'x_pos',
        'y_pos',
        'width',
        'height',
        'is_visible',
        'wrapper_id',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'x_pos' => 'integer',
        'y_pos' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'wrapper_id' => 'integer',
    ];
}