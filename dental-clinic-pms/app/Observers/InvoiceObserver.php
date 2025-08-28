<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Services\AuditLogService;

class InvoiceObserver
{
    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        AuditLogService::logModelChange($invoice, 'created');
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        // Get the original values before the update
        $oldValues = $invoice->getOriginal();
        $newValues = $invoice->getAttributes();
        
        AuditLogService::logModelChange($invoice, 'updated', $oldValues, $newValues);
    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void
    {
        AuditLogService::logModelChange($invoice, 'deleted');
    }

    /**
     * Handle the Invoice "restored" event.
     */
    public function restored(Invoice $invoice): void
    {
        AuditLogService::logModelChange($invoice, 'restored');
    }

    /**
     * Handle the Invoice "force deleted" event.
     */
    public function forceDeleted(Invoice $invoice): void
    {
        AuditLogService::logModelChange($invoice, 'force_deleted');
    }
}