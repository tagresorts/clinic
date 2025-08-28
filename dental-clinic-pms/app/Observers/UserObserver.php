<?php

namespace App\Observers;

use App\Models\User;
use App\Services\AuditLogService;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        AuditLogService::logModelChange($user, 'created');
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Get the original values before the update
        $oldValues = $user->getOriginal();
        $newValues = $user->getAttributes();
        
        AuditLogService::logModelChange($user, 'updated', $oldValues, $newValues);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        AuditLogService::logModelChange($user, 'deleted');
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        AuditLogService::logModelChange($user, 'restored');
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        AuditLogService::logModelChange($user, 'force_deleted');
    }
}