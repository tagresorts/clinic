<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Services\AuditLogService;

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        AuditLogService::logModelChange($appointment, 'created');
    }

    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        // Get the original values before the update
        $oldValues = $appointment->getOriginal();
        $newValues = $appointment->getAttributes();
        
        // Log the change
        AuditLogService::logModelChange($appointment, 'updated', $oldValues, $newValues);
        
        // Business logic for appointment conflicts
        if ($appointment->isDirty('appointment_datetime')) {
            if (Appointment::hasConflict($appointment->dentist_id, $appointment->appointment_datetime, $appointment->duration_minutes, $appointment->id)) {
                throw new \Exception('The selected dentist has a conflicting appointment at that time.');
            }
        }

        // Update treatment plan if associated
        if ($appointment->treatment_plan_id) {
            $treatmentPlan = $appointment->treatmentPlan;
            $appointments = $treatmentPlan->appointments;

            $completedAppointments = $appointments->where('status', 'completed')->count();

            $treatmentPlan->update([
                'estimated_duration_sessions' => $appointments->count(),
            ]);

            if ($completedAppointments === $appointments->count()) {
                $treatmentPlan->update([
                    'status' => 'completed',
                ]);
            }
        }
    }

    /**
     * Handle the Appointment "deleted" event.
     */
    public function deleted(Appointment $appointment): void
    {
        AuditLogService::logModelChange($appointment, 'deleted');
    }

    /**
     * Handle the Appointment "restored" event.
     */
    public function restored(Appointment $appointment): void
    {
        AuditLogService::logModelChange($appointment, 'restored');
    }

    /**
     * Handle the Appointment "force deleted" event.
     */
    public function forceDeleted(Appointment $appointment): void
    {
        AuditLogService::logModelChange($appointment, 'force_deleted');
    }
}
