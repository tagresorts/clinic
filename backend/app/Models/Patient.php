<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zip_code',
        'emergency_contact',
        'emergency_phone',
        'medical_history',
        'dental_history',
        'allergies',
        'medications',
        'insurance_provider',
        'insurance_number',
        'notes',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the patient's appointments.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the patient's treatment plans.
     */
    public function treatmentPlans()
    {
        return $this->hasMany(TreatmentPlan::class);
    }

    /**
     * Get the patient's treatment records.
     */
    public function treatmentRecords()
    {
        return $this->hasMany(TreatmentRecord::class);
    }

    /**
     * Get the patient's invoices.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the patient's dental charts.
     */
    public function dentalCharts()
    {
        return $this->hasMany(DentalChart::class);
    }

    /**
     * Get the patient's files.
     */
    public function patientFiles()
    {
        return $this->hasMany(PatientFile::class);
    }

    /**
     * Get the patient's full name.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the patient's age.
     */
    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    /**
     * Scope to get only active patients.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to search patients by name, phone, or email.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    /**
     * Get upcoming appointments for this patient.
     */
    public function upcomingAppointments()
    {
        return $this->appointments()
                    ->where('date_time', '>=', now())
                    ->whereIn('status', ['scheduled', 'confirmed'])
                    ->orderBy('date_time');
    }

    /**
     * Get the patient's latest treatment plan.
     */
    public function latestTreatmentPlan()
    {
        return $this->treatmentPlans()->latest()->first();
    }

    /**
     * Get outstanding invoice balance.
     */
    public function getOutstandingBalanceAttribute()
    {
        return $this->invoices()
                    ->whereIn('payment_status', ['pending', 'partial'])
                    ->sum('outstanding_balance');
    }
}