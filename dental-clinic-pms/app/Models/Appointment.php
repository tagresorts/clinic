<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * @property-read Patient $patient
 * @property-read User $dentist
 * @property bool $reminder_sent
 */
class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'dentist_id',
        'appointment_datetime',
        'duration_minutes',
        'appointment_type',
        'status',
        'reason_for_visit',
        'appointment_notes',
        'cancellation_reason',
        'reminder_sent',
        'reminder_sent_at',
    ];

    protected $casts = [
        'appointment_datetime' => 'datetime',
        'reminder_sent' => 'boolean',
        'reminder_sent_at' => 'datetime',
    ];

    // Status constants
    const STATUS_TENTATIVE = 'tentative';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_NO_SHOW = 'no_show';

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_TENTATIVE,
            self::STATUS_SCHEDULED,
            self::STATUS_CONFIRMED,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
            self::STATUS_NO_SHOW,
        ];
    }

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
     * Treatment records for this appointment
     */
    public function treatmentRecords(): HasMany
    {
        return $this->hasMany(TreatmentRecord::class);
    }

    /**
     * Treatment plan for this appointment
     */
    public function treatmentPlan(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlan::class);
    }

    /**
     * Get end time of appointment
     */
    public function getEndTimeAttribute(): Carbon
    {
        return $this->appointment_datetime->addMinutes($this->duration_minutes);
    }

    /**
     * Get formatted appointment time
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->appointment_datetime->format('M j, Y g:i A');
    }

    /**
     * Get time until appointment
     */
    public function getTimeUntilAttribute(): string
    {
        if ($this->appointment_datetime->isPast()) {
            return 'Past';
        }
        return $this->appointment_datetime->diffForHumans();
    }

    /**
     * Check if appointment is today
     */
    public function isToday(): bool
    {
        return $this->appointment_datetime->isToday();
    }

    /**
     * Check if appointment is upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->appointment_datetime->isFuture() && 
               in_array($this->status, [self::STATUS_SCHEDULED, self::STATUS_CONFIRMED]);
    }

    /**
     * Check if appointment can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_SCHEDULED, self::STATUS_CONFIRMED]) &&
               $this->appointment_datetime->isFuture();
    }

    /**
     * Check if appointment can be rescheduled
     */
    public function canBeRescheduled(): bool
    {
        return $this->canBeCancelled();
    }

    /**
     * Check if reminder should be sent
     */
    public function shouldSendReminder(): bool
    {
        return !$this->reminder_sent &&
               $this->appointment_datetime->isFuture() &&
               $this->appointment_datetime->diffInHours(now()) <= 24 &&
               in_array($this->status, [self::STATUS_SCHEDULED, self::STATUS_CONFIRMED]);
    }

    /**
     * Mark reminder as sent
     */
    public function markReminderSent(): void
    {
        $this->update([
            'reminder_sent' => true,
            'reminder_sent_at' => now(),
        ]);
    }

    /*
     * Add modification to history
     */
    // public function addModificationHistory(string $action, array $data = []): void
    // {
    //     $history = $this->modification_history ?? [];
    //     $history[] = [
    //         'action' => $action,
    //         'data' => $data,
    //         'timestamp' => now()->toISOString(),
    //         'user_id' => auth()->id(),
    //     ];
    //     $this->update(['modification_history' => $history]);
    // }

    /**
     * Scope for today's appointments
     */
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_datetime', now()->today());
    }

    /**
     * Scope for tomorrow's appointments
     */
    public function scopeTomorrow($query)
    {
        return $query->whereDate('appointment_datetime', now()->tomorrow());
    }

    /**
     * Scope for upcoming appointments
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_datetime', '>', now())
                    ->whereIn('status', [self::STATUS_SCHEDULED, self::STATUS_CONFIRMED]);
    }

    /**
     * Scope for past appointments
     */
    public function scopePast($query)
    {
        return $query->where('appointment_datetime', '<', now());
    }

    /**
     * Scope by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope by dentist
     */
    public function scopeByDentist($query, int $dentistId)
    {
        return $query->where('dentist_id', $dentistId);
    }

    /**
     * Scope by patient
     */
    public function scopeByPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope for appointments within date range
     */
    public function scopeBetweenDates($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('appointment_datetime', [$startDate, $endDate]);
    }

    /**
     * Scope for appointments needing reminders
     */
    public function scopeNeedingReminders($query)
    {
        return $query->where('reminder_sent', false)
                    ->where('appointment_datetime', '>', now())
                    ->where('appointment_datetime', '<=', now()->addHours(24))
                    ->whereIn('status', [self::STATUS_SCHEDULED, self::STATUS_CONFIRMED]);
    }

    /**
     * Check for scheduling conflicts
     */
    public static function hasConflict(int $dentistId, Carbon $datetime, int $duration = 30, ?int $excludeId = null): bool
    {
        $endTime = $datetime->copy()->addMinutes($duration);
        
        $query = static::where('dentist_id', $dentistId)
            ->whereIn('status', [self::STATUS_SCHEDULED, self::STATUS_CONFIRMED, self::STATUS_IN_PROGRESS])
            ->where(function ($q) use ($datetime, $endTime) {
                $q->whereBetween('appointment_datetime', [$datetime, $endTime])
                  ->orWhere(function ($q2) use ($datetime) {
                      $q2->where('appointment_datetime', '<=', $datetime)
                         ->whereRaw('DATE_ADD(appointment_datetime, INTERVAL duration_minutes MINUTE) > ?', [$datetime]);
                  });
            });
            
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
}
