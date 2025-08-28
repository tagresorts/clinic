<?php

namespace App\Observers;

use App\Models\TreatmentPlan;
use App\Services\AuditLogService;

class TreatmentPlanObserver
{
    /**
     * Handle the TreatmentPlan "created" event.
     */
    public function created(TreatmentPlan $treatmentPlan): void
    {
        AuditLogService::logModelChange($treatmentPlan, 'created');
    }

    /**
     * Handle the TreatmentPlan "updated" event.
     */
    public function updated(TreatmentPlan $treatmentPlan): void
    {
        // Get the original values before the update
        $oldValues = $treatmentPlan->getOriginal();
        $newValues = $treatmentPlan->getAttributes();
        
        AuditLogService::logModelChange($treatmentPlan, 'updated', $oldValues, $newValues);
    }

    /**
     * Handle the TreatmentPlan "deleted" event.
     */
    public function deleted(TreatmentPlan $treatmentPlan): void
    {
        AuditLogService::logModelChange($treatmentPlan, 'deleted');
    }

    /**
     * Handle the TreatmentPlan "restored" event.
     */
    public function restored(TreatmentPlan $treatmentPlan): void
    {
        AuditLogService::logModelChange($treatmentPlan, 'restored');
    }

    /**
     * Handle the TreatmentPlan "force deleted" event.
     */
    public function forceDeleted(TreatmentPlan $treatmentPlan): void
    {
        AuditLogService::logModelChange($treatmentPlan, 'force_deleted');
    }
}