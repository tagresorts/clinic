<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'created_by', 'invoice_number', 'invoice_date', 'due_date',
        'subtotal', 'tax_rate', 'tax_amount', 'discount_amount', 'total_amount',
        'amount_paid', 'outstanding_balance', 'status', 'payment_status',
        'line_items', 'notes', 'terms_and_conditions', 'insurance_claim_submitted',
        'insurance_claim_number', 'insurance_covered_amount', 'patient_responsibility',
        'sent_at', 'paid_at',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:4',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'insurance_covered_amount' => 'decimal:2',
        'patient_responsibility' => 'decimal:2',
        'line_items' => 'array',
        'insurance_claim_submitted' => 'boolean',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
}
