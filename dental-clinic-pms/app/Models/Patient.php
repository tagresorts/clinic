<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read string $full_name
 */
class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'source',
        'address',
        'phone',
        'email',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'allergies',
        'medical_conditions',
        'current_medications',
        'medical_notes',
        'dental_history',
        'previous_treatments',
        'dental_notes',
        'insurance_provider',
        'insurance_policy_number',
        'insurance_group_number',
        'insurance_expiry_date',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'insurance_expiry_date' => 'date',
    ];

    /**
     * Generate unique patient ID on creation
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($patient) {
            if (empty($patient->patient_id)) {
                $patient->patient_id = static::generatePatientId();
            }
        });
    }

    /**
     * Generate a unique patient ID
     */
    protected static function generatePatientId(): string
    {
        $prefix = 'PAT';
        $year = date('Y');
        
        // Find the last patient ID for the current year to avoid race conditions and issues with out-of-order creation.
        $lastPatient = static::where('patient_id', 'like', $prefix . $year . '%')
            ->orderBy('patient_id', 'desc')
            ->first();
        
        if ($lastPatient) {
            // Extract the number from the last patient_id, e.g., from "PAT20250012"
            $lastNumber = intval(substr($lastPatient->patient_id, strlen($prefix) + 4));
            $number = $lastNumber + 1;
        } else {
            // First patient of the year
            $number = 1;
        }
        
        return $prefix . $year . str_pad((string) $number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get age attribute
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    /**
     * Appointments for this patient
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Treatment plans for this patient
     */
    public function treatmentPlans(): HasMany
    {
        return $this->hasMany(TreatmentPlan::class);
    }

    /**
     * Treatment records for this patient
     */
    public function treatmentRecords(): HasMany
    {
        return $this->hasMany(TreatmentRecord::class);
    }

    /**
     * Invoices for this patient
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Payments made by this patient
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Dental chart for this patient
     */
    public function dentalCharts(): HasMany
    {
        return $this->hasMany(DentalChart::class);
    }

    /**
     * Get upcoming appointments
     */
    public function upcomingAppointments()
    {
        return $this->appointments()
            ->where('appointment_datetime', '>', now())
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->orderBy('appointment_datetime');
    }

    /**
     * Get active treatment plans
     */
    public function activeTreatmentPlans()
    {
        return $this->treatmentPlans()
            ->whereIn('status', ['patient_approved', 'in_progress']);
    }

    /**
     * Get outstanding balance
     */
    public function getOutstandingBalanceAttribute(): float
    {
        return $this->invoices()
            ->whereIn('status', ['sent', 'partially_paid', 'overdue'])
            ->sum('outstanding_balance');
    }

    /**
     * Check if patient has any allergies
     */
    public function hasAllergies(): bool
    {
        return !empty($this->allergies);
    }

    /**
     * Check if patient has active insurance
     */
    public function hasActiveInsurance(): bool
    {
        return !empty($this->insurance_provider) && 
               ($this->insurance_expiry_date === null || $this->insurance_expiry_date->isFuture());
    }

    /**
     * Scope to get only active patients
     * Note: is_active column was removed, so this scope now returns all non-deleted patients
     */
    public function scopeActive($query)
    {
        return $query; // Return all patients since is_active column was removed
    }

    /**
     * Scope to search patients by name
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('patient_id', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to get patients with upcoming appointments
     */
    public function scopeWithUpcomingAppointments($query)
    {
        return $query->whereHas('appointments', function ($q) {
            $q->where('appointment_datetime', '>', now())
              ->whereIn('status', ['scheduled', 'confirmed']);
        });
    }

    /**
     * Scope to get patients with outstanding balance
     */
    public function scopeWithOutstandingBalance($query)
    {
        return $query->whereHas('invoices', function ($q) {
            $q->whereIn('status', ['sent', 'partially_paid', 'overdue'])
              ->where('outstanding_balance', '>', 0);
        });
    }
}
