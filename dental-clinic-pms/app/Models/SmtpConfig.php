<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmtpConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','host','port','encryption','username','password','from_email','from_name','is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];
}
