<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id', 'patient_id', 'received_by', 'payment_reference',
        'payment_date', 'amount', 'payment_method', 'transaction_id',
        'check_number', 'bank_reference', 'card_last_four', 'status',
        'notes', 'receipt_generated', 'receipt_number', 'refund_of_payment_id',
        'refund_amount', 'refund_reason',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'receipt_generated' => 'boolean',
    ];

    public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function receivedBy(): BelongsTo { return $this->belongsTo(User::class, 'received_by'); }
    public function refundOfPayment(): BelongsTo { return $this->belongsTo(Payment::class, 'refund_of_payment_id'); }
    public function refunds(): HasMany { return $this->hasMany(Payment::class, 'refund_of_payment_id'); }
}
