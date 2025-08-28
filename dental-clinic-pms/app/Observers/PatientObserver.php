<?php

namespace App\Observers;

use App\Models\Patient;
use App\Services\AuditLogService;

class PatientObserver
{
    /**
     * Handle the Patient "created" event.
     */
    public function created(Patient $patient): void
    {
        AuditLogService::logModelChange($patient, 'created');
    }

    /**
     * Handle the Patient "updated" event.
     */
    public function updated(Patient $patient): void
    {
        // Get the original values before the update
        $oldValues = $patient->getOriginal();
        $newValues = $patient->getAttributes();
        
        AuditLogService::logModelChange($patient, 'updated', $oldValues, $newValues);
    }

    /**
     * Handle the Patient "deleted" event.
     */
    public function deleted(Patient $patient): void
    {
        AuditLogService::logModelChange($patient, 'deleted');
    }

    /**
     * Handle the Patient "restored" event.
     */
    public function restored(Patient $patient): void
    {
        AuditLogService::logModelChange($patient, 'restored');
    }

    /**
     * Handle the Patient "force deleted" event.
     */
    public function forceDeleted(Patient $patient): void
    {
        AuditLogService::logModelChange($patient, 'force_deleted');
    }
}