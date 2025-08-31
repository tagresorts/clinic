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
        'patient_id', 'appointment_id', 'treatment_plan_id', 'total_amount',
        'outstanding_balance', 'status', 'payment_status', 'due_date', 'user_id', 
        'paid_at', 'sent_at', 'invoice_items', 'invoice_number',
    ];

    protected $casts = [
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'paid_at' => 'datetime',
        'sent_at' => 'datetime',
        'invoice_items' => 'array',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function appointment(): BelongsTo { return $this->belongsTo(Appointment::class); }
    public function treatmentPlan(): BelongsTo { return $this->belongsTo(TreatmentPlan::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }

    /**
     * Generate invoice number
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = 'INV-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
