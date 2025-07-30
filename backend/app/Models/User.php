<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Get the appointments assigned to this dentist.
     */
    public function appointmentsAsDentist()
    {
        return $this->hasMany(Appointment::class, 'dentist_id');
    }

    /**
     * Get the treatment plans created by this dentist.
     */
    public function treatmentPlans()
    {
        return $this->hasMany(TreatmentPlan::class, 'dentist_id');
    }

    /**
     * Get the treatment records created by this dentist.
     */
    public function treatmentRecords()
    {
        return $this->hasMany(TreatmentRecord::class, 'dentist_id');
    }

    /**
     * Get the invoices created by this user.
     */
    public function createdInvoices()
    {
        return $this->hasMany(Invoice::class, 'created_by_id');
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Check if user is administrator.
     */
    public function isAdministrator()
    {
        return $this->hasRole('administrator');
    }

    /**
     * Check if user is dentist.
     */
    public function isDentist()
    {
        return $this->hasRole('dentist');
    }

    /**
     * Check if user is receptionist.
     */
    public function isReceptionist()
    {
        return $this->hasRole('receptionist');
    }

    /**
     * Scope to get only active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only dentists.
     */
    public function scopeDentists($query)
    {
        return $query->role('dentist');
    }
}