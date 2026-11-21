<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any appointments.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the appointment.
     *
     * Dentists may only view their own appointments; all other roles
     * (administrator, receptionist) may view any appointment.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        if ($this->isDentist($user)) {
            return $appointment->dentist_id === $user->id;
        }

        return true;
    }

    /**
     * Determine whether the user can create appointments.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['administrator', 'receptionist'])
            || $user->can('appointment-create');
    }

    /**
     * Determine whether the user can update the appointment.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        return $user->hasRole(['administrator', 'receptionist']);
    }

    /**
     * Determine whether the user can cancel/confirm/complete the appointment.
     */
    public function modifyStatus(User $user, Appointment $appointment): bool
    {
        return $user->hasRole(['administrator', 'receptionist', 'dentist']);
    }

    /**
     * Determine whether the user can complete the appointment.
     */
    public function complete(User $user, Appointment $appointment): bool
    {
        return $user->hasRole(['administrator', 'dentist']);
    }

    /**
     * Determine whether the user can delete the appointment.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->hasRole('administrator');
    }

    /**
     * Safely determine whether the user has the dentist role, tolerating a
     * misconfigured permission system (consistent with RoleMiddleware).
     */
    private function isDentist(User $user): bool
    {
        try {
            return method_exists($user, 'hasRole') && $user->hasRole('dentist');
        } catch (\Throwable $e) {
            return false;
        }
    }
}
