<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'dentist_id',
        'date_time',
        'duration',
        'type',
        'status',
        'notes',
        'reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_time' => 'datetime',
        'duration' => 'integer',
    ];

    /**
     * Appointment types.
     */
    const TYPES = [
        'consultation' => 'Consultation',
        'cleaning' => 'Cleaning',
        'extraction' => 'Extraction',
        'filling' => 'Filling',
        'root_canal' => 'Root Canal',
        'crown' => 'Crown',
        'bridge' => 'Bridge',
        'implant' => 'Implant',
        'orthodontics' => 'Orthodontics',
        'emergency' => 'Emergency',
        'followup' => 'Follow-up',
    ];

    /**
     * Appointment statuses.
     */
    const STATUSES = [
        'scheduled' => 'Scheduled',
        'confirmed' => 'Confirmed',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'no_show' => 'No Show',
    ];

    /**
     * Get the patient that owns the appointment.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the dentist assigned to the appointment.
     */
    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }

    /**
     * Get the treatment records for this appointment.
     */
    public function treatmentRecords()
    {
        return $this->hasMany(TreatmentRecord::class);
    }

    /**
     * Get the end time of the appointment.
     */
    public function getEndTimeAttribute()
    {
        return $this->date_time->addMinutes($this->duration);
    }

    /**
     * Check if appointment is in the past.
     */
    public function getIsPastAttribute()
    {
        return $this->date_time->isPast();
    }

    /**
     * Check if appointment is today.
     */
    public function getIsTodayAttribute()
    {
        return $this->date_time->isToday();
    }

    /**
     * Check if appointment is upcoming (future).
     */
    public function getIsUpcomingAttribute()
    {
        return $this->date_time->isFuture();
    }

    /**
     * Get formatted date and time.
     */
    public function getFormattedDateTimeAttribute()
    {
        return $this->date_time->format('M j, Y g:i A');
    }

    /**
     * Get the appointment type label.
     */
    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get the appointment status label.
     */
    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Scope to get appointments for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date_time', $date);
    }

    /**
     * Scope to get appointments between dates.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_time', [$startDate, $endDate]);
    }

    /**
     * Scope to get appointments for a specific dentist.
     */
    public function scopeForDentist($query, $dentistId)
    {
        return $query->where('dentist_id', $dentistId);
    }

    /**
     * Scope to get appointments with specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get upcoming appointments.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date_time', '>=', now())
                    ->whereIn('status', ['scheduled', 'confirmed']);
    }

    /**
     * Scope to get today's appointments.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date_time', today());
    }

    /**
     * Check for appointment conflicts.
     */
    public static function hasConflict($dentistId, $dateTime, $duration, $excludeId = null)
    {
        $startTime = Carbon::parse($dateTime);
        $endTime = $startTime->copy()->addMinutes($duration);

        $query = self::where('dentist_id', $dentistId)
                    ->where('status', '!=', 'cancelled')
                    ->where(function ($q) use ($startTime, $endTime) {
                        $q->where(function ($subQ) use ($startTime, $endTime) {
                            $subQ->where('date_time', '<', $endTime)
                                 ->whereRaw('DATE_ADD(date_time, INTERVAL duration MINUTE) > ?', [$startTime]);
                        });
                    });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get available time slots for a dentist on a specific date.
     */
    public static function getAvailableSlots($dentistId, $date, $duration = 60)
    {
        $workingHours = [
            'start' => '08:00',
            'end' => '18:00',
            'interval' => 30, // minutes
        ];

        $startTime = Carbon::parse($date . ' ' . $workingHours['start']);
        $endTime = Carbon::parse($date . ' ' . $workingHours['end']);
        $slots = [];

        // Get existing appointments for the day
        $appointments = self::where('dentist_id', $dentistId)
                           ->whereDate('date_time', $date)
                           ->where('status', '!=', 'cancelled')
                           ->get();

        $currentTime = $startTime->copy();

        while ($currentTime->addMinutes($workingHours['interval'])->lte($endTime)) {
            $slotEnd = $currentTime->copy()->addMinutes($duration);

            if ($slotEnd->lte($endTime)) {
                $hasConflict = false;

                foreach ($appointments as $appointment) {
                    $appointmentStart = $appointment->date_time;
                    $appointmentEnd = $appointmentStart->copy()->addMinutes($appointment->duration);

                    if ($currentTime->lt($appointmentEnd) && $slotEnd->gt($appointmentStart)) {
                        $hasConflict = true;
                        break;
                    }
                }

                if (!$hasConflict) {
                    $slots[] = $currentTime->format('H:i');
                }
            }
        }

        return $slots;
    }

    /**
     * Send appointment reminder.
     */
    public function sendReminder()
    {
        // Implementation would depend on notification service
        // This is a placeholder for the reminder functionality
        return true;
    }
}