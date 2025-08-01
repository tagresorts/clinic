<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'user_name', 'user_role', 'action', 'entity_type',
        'entity_id', 'entity_description', 'ip_address', 'user_agent',
        'request_method', 'request_url', 'old_values', 'new_values',
        'changed_fields', 'description', 'metadata', 'severity',
        'session_id', 'event_time', 'is_sensitive_data', 'requires_review',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_fields' => 'array',
        'metadata' => 'array',
        'event_time' => 'datetime',
        'is_sensitive_data' => 'boolean',
        'requires_review' => 'boolean',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
