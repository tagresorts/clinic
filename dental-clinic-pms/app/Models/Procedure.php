<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'cost',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];
}
