<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TreatmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'dentist_id',
        'plan_title',
        'diagnosis',
        // 'proposed_procedures', // Removed as it will be handled by many-to-many
        'estimated_cost',
        'estimated_duration_sessions',
        'priority',
        'status',
        'approved_at',
        'started_at',
        'completed_at',
        'treatment_notes',
        'patient_concerns',
        'dentist_notes',
        'actual_cost',
        'insurance_covered',
        'insurance_coverage_amount',
    ];

    protected $casts = [
        // 'proposed_procedures' => 'array', // Removed as it will be handled by many-to-many
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'insurance_coverage_amount' => 'decimal:2',
        'insurance_covered' => 'boolean',
        'approved_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // ... (other methods)

    /**
     * Procedures for this treatment plan
     */
    public function procedures(): BelongsToMany
    {
        return $this->belongsToMany(Procedure::class, 'treatment_plan_procedure');
    }

    // Status constants
    const STATUS_PROPOSED = 'proposed';
    const STATUS_PATIENT_APPROVED = 'patient_approved';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Patient relationship
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Dentist relationship
     */
    public function dentist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }

    /**
     * Treatment records for this plan
     */
    public function treatmentRecords(): HasMany
    {
        return $this->hasMany(TreatmentRecord::class);
    }

    /**
     * Check if plan is active
     */
    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_PATIENT_APPROVED, self::STATUS_IN_PROGRESS]);
    }

    /**
     * Calculate patient's out-of-pocket cost
     */
    public function getPatientCostAttribute(): float
    {
        $cost = $this->actual_cost ?? $this->estimated_cost;
        return $cost - ($this->insurance_coverage_amount ?? 0);
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute(): int
    {
        if ($this->status === self::STATUS_COMPLETED) {
            return 100;
        }
        
        if ($this->status === self::STATUS_CANCELLED) {
            return 0;
        }

        $completedSessions = $this->treatmentRecords()->count();
        $totalSessions = $this->estimated_duration_sessions ?? 1;
        
        return min(100, round(($completedSessions / $totalSessions) * 100));
    }

    /**
     * Scope by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope active plans
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PATIENT_APPROVED, self::STATUS_IN_PROGRESS]);
    }

    /**
     * Scope by priority
     */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope by dentist
     */
    public function scopeByDentist($query, int $dentistId)
    {
        return $query->where('dentist_id', $dentistId);
    }
}
