<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Appointments assigned to this dentist
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'dentist_id');
    }

    /**
     * Treatment plans created by this dentist
     */
    public function treatmentPlans(): HasMany
    {
        return $this->hasMany(TreatmentPlan::class, 'dentist_id');
    }

    /**
     * Treatment records created by this dentist
     */
    public function treatmentRecords(): HasMany
    {
        return $this->hasMany(TreatmentRecord::class, 'dentist_id');
    }

    /**
     * Invoices created by this user
     */
    public function createdInvoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }

    /**
     * Payments received by this user
     */
    public function receivedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'received_by');
    }

    /**
     * Dental chart updates made by this user
     */
    public function dentalChartUpdates(): HasMany
    {
        return $this->hasMany(DentalChart::class, 'updated_by');
    }

    /**
     * Audit logs created by this user
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    /**
     * Scope to get only active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
