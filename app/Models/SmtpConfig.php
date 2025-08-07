<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmtpConfig extends Model
{
    use HasFactory;

    protected $table = 'smtp_configurations';

    protected $fillable = [
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'is_default',
    ];
}
