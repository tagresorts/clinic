<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as RequestFacade;

class AuditLogService
{
    /**
     * Log a model change (create, update, delete)
     */
    public static function logModelChange(Model $model, string $action, ?array $oldValues = null, ?array $newValues = null): void
    {
        try {
            $user = Auth::user();
            
            // Determine what changed
            $changedFields = [];
            if ($action === 'updated' && $oldValues && $newValues) {
                $changedFields = array_keys(array_diff_assoc($newValues, $oldValues));
            }

            // Filter out sensitive fields
            $filteredOldValues = self::filterSensitiveData($oldValues ?? []);
            $filteredNewValues = self::filterSensitiveData($newValues ?? []);

            AuditLog::create([
                'user_id' => $user?->id,
                'user_name' => $user?->name ?? 'System',
                'user_role' => $user?->getRoleNames()->first() ?? 'Unknown',
                'action' => $action,
                'entity_type' => get_class($model),
                'entity_id' => $model->id ?? null,
                'entity_description' => self::getEntityDescription($model),
                'ip_address' => RequestFacade::ip(),
                'user_agent' => RequestFacade::userAgent(),
                'request_method' => RequestFacade::method(),
                'request_url' => RequestFacade::fullUrl(),
                'old_values' => $filteredOldValues,
                'new_values' => $filteredNewValues,
                'changed_fields' => $changedFields,
                'description' => self::generateDescription($action, $model, $changedFields),
                'metadata' => [
                    'route' => RequestFacade::route()?->getName(),
                    'session_id' => session()->getId(),
                    'referrer' => RequestFacade::header('referer'),
                ],
                'severity' => self::determineSeverity($action, $model),
                'session_id' => session()->getId(),
                'event_time' => now(),
                'is_sensitive_data' => self::containsSensitiveData($oldValues, $newValues),
                'requires_review' => self::requiresReview($action, $model),
            ]);
        } catch (\Exception $e) {
            // Log the error but don't break the application
            \Log::error('Audit logging failed: ' . $e->getMessage(), [
                'action' => $action,
                'model' => get_class($model),
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Log a specific frontend action
     */
    public static function logFrontendAction(string $action, ?Model $entity = null, array $metadata = [], string $description = null): void
    {
        try {
            $user = Auth::user();

            $oldValues = $metadata['old_settings'] ?? null;
            $newValues = $metadata['new_settings'] ?? null;
            unset($metadata['old_settings'], $metadata['new_settings']);

            $changedFields = null;
            if ($oldValues && $newValues) {
                $changedFields = array_keys(array_diff_assoc($newValues, $oldValues));
            }

            AuditLog::create([
                'user_id' => $user?->id,
                'user_name' => $user?->name ?? 'System',
                'user_role' => $user?->getRoleNames()->first() ?? 'Unknown',
                'action' => $action,
                'entity_type' => $entity ? get_class($entity) : null,
                'entity_id' => $entity?->id,
                'entity_description' => $entity ? self::getEntityDescription($entity) : 'Operational Settings',
                'ip_address' => RequestFacade::ip(),
                'user_agent' => RequestFacade::userAgent(),
                'request_method' => RequestFacade::method(),
                'request_url' => RequestFacade::fullUrl(),
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'changed_fields' => $changedFields,
                'description' => $description ?? self::generateActionDescription($action, $entity, $changedFields),
                'metadata' => array_merge([
                    'route' => RequestFacade::route()?->getName(),
                    'session_id' => session()->getId(),
                    'referrer' => RequestFacade::header('referer'),
                ], $metadata),
                'severity' => 'info',
                'session_id' => session()->getId(),
                'event_time' => now(),
                'is_sensitive_data' => false,
                'requires_review' => false,
            ]);
        } catch (\Exception $e) {
            \Log::error('Frontend action logging failed: ' . $e->getMessage(), [
                'action' => $action,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Log user authentication events
     */
    public static function logAuthEvent(string $action, array $metadata = []): void
    {
        try {
            $user = Auth::user();
            
            AuditLog::create([
                'user_id' => $user?->id,
                'user_name' => $user?->name ?? 'Unknown',
                'user_role' => $user?->getRoleNames()->first() ?? 'Unknown',
                'action' => $action,
                'entity_type' => 'User',
                'entity_id' => $user?->id,
                'entity_description' => $user ? "User: {$user->name} ({$user->email})" : 'Unknown User',
                'ip_address' => RequestFacade::ip(),
                'user_agent' => RequestFacade::userAgent(),
                'request_method' => RequestFacade::method(),
                'request_url' => RequestFacade::fullUrl(),
                'old_values' => null,
                'new_values' => null,
                'changed_fields' => null,
                'description' => self::generateAuthDescription($action, $user),
                'metadata' => array_merge([
                    'route' => RequestFacade::route()?->getName(),
                    'session_id' => session()->getId(),
                    'referrer' => RequestFacade::header('referer'),
                ], $metadata),
                'severity' => 'info',
                'session_id' => session()->getId(),
                'event_time' => now(),
                'is_sensitive_data' => false,
                'requires_review' => false,
            ]);
        } catch (\Exception $e) {
            \Log::error('Auth event logging failed: ' . $e->getMessage(), [
                'action' => $action,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Log system events
     */
    public static function logSystemEvent(string $action, string $description, array $metadata = [], string $severity = 'info'): void
    {
        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()?->name ?? 'System',
                'user_role' => Auth::user()?->getRoleNames()->first() ?? 'System',
                'action' => $action,
                'entity_type' => 'System',
                'entity_id' => null,
                'entity_description' => 'System Event',
                'ip_address' => RequestFacade::ip(),
                'user_agent' => RequestFacade::userAgent(),
                'request_method' => RequestFacade::method(),
                'request_url' => RequestFacade::fullUrl(),
                'old_values' => null,
                'new_values' => null,
                'changed_fields' => null,
                'description' => $description,
                'metadata' => array_merge([
                    'route' => RequestFacade::route()?->getName(),
                    'session_id' => session()->getId(),
                ], $metadata),
                'severity' => $severity,
                'session_id' => session()->getId(),
                'event_time' => now(),
                'is_sensitive_data' => false,
                'requires_review' => false,
            ]);
        } catch (\Exception $e) {
            \Log::error('System event logging failed: ' . $e->getMessage(), [
                'action' => $action,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get a human-readable description of the entity
     */
    protected static function getEntityDescription(Model $model): string
    {
        if (method_exists($model, 'getAuditDescription')) {
            return $model->getAuditDescription();
        }

        // Default descriptions for common models
        $modelName = class_basename($model);
        
        switch ($modelName) {
            case 'Patient':
                return "Patient: {$model->first_name} {$model->last_name}";
            case 'Appointment':
                return "Appointment: {$model->patient->first_name} {$model->patient->last_name} - " . 
                       $model->appointment_datetime->format('M d, Y H:i');
            case 'User':
                return "User: {$model->name} ({$model->email})";
            case 'TreatmentPlan':
                return "Treatment Plan: {$model->plan_title} for {$model->patient->first_name} {$model->patient->last_name}";
            case 'Invoice':
                return "Invoice: #{$model->id} - {$model->patient->first_name} {$model->patient->last_name}";
            default:
                return "{$modelName}: ID {$model->id}";
        }
    }

    /**
     * Generate a description for model changes
     */
    protected static function generateDescription(string $action, Model $model, array $changedFields): string
    {
        $entityDesc = self::getEntityDescription($model);
        
        switch ($action) {
            case 'created':
                return "Created {$entityDesc}";
            case 'updated':
                if (empty($changedFields)) {
                    return "Updated {$entityDesc} (no visible changes)";
                }
                $fields = implode(', ', array_slice($changedFields, 0, 3));
                if (count($changedFields) > 3) {
                    $fields .= ' and ' . (count($changedFields) - 3) . ' more';
                }
                return "Updated {$entityDesc} - Changed: {$fields}";
            case 'deleted':
                return "Deleted {$entityDesc}";
            case 'restored':
                return "Restored {$entityDesc}";
            default:
                return ucfirst($action) . " {$entityDesc}";
        }
    }

    /**
     * Generate description for frontend actions
     */
    protected static function generateActionDescription(string $action, ?Model $entity, ?array $changedFields = []): string
    {
        $description = ucfirst(str_replace('_', ' ', $action));

        if ($entity) {
            $description .= ' ' . self::getEntityDescription($entity);
        }

        if (!empty($changedFields)) {
            $fields = implode(', ', array_map('ucwords', str_replace('_', ' ', $changedFields)));
            $description .= ' - Changed: ' . $fields;
        }
        
        return $description;
    }

    /**
     * Generate description for authentication events
     */
    protected static function generateAuthDescription(string $action, $user): string
    {
        switch ($action) {
            case 'login':
                return "User {$user->name} logged in";
            case 'logout':
                return "User {$user->name} logged out";
            case 'failed_login':
                return "Failed login attempt";
            case 'password_reset':
                return "Password reset requested";
            case 'password_changed':
                return "Password changed";
            default:
                return ucfirst($action);
        }
    }

    /**
     * Determine severity level
     */
    protected static function determineSeverity(string $action, Model $model): string
    {
        // High severity actions
        if (in_array($action, ['deleted', 'restored'])) {
            return 'high';
        }

        // Medium severity actions
        if (in_array($action, ['updated']) && in_array(class_basename($model), ['User', 'Patient', 'Appointment'])) {
            return 'medium';
        }

        // Low severity actions
        return 'low';
    }

    /**
     * Check if data contains sensitive information
     */
    protected static function containsSensitiveData(?array $oldValues, ?array $newValues): bool
    {
        $sensitiveFields = ['password', 'ssn', 'credit_card', 'bank_account', 'medical_record'];
        
        $allValues = array_merge($oldValues ?? [], $newValues ?? []);
        
        foreach ($sensitiveFields as $field) {
            if (array_key_exists($field, $allValues)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if action requires review
     */
    protected static function requiresReview(string $action, Model $model): bool
    {
        // High-risk actions that should be reviewed
        if (in_array($action, ['deleted', 'restored'])) {
            return true;
        }

        // Sensitive models
        if (in_array(class_basename($model), ['User', 'Patient', 'Invoice'])) {
            return true;
        }

        return false;
    }

    /**
     * Filter out sensitive data from values
     */
    protected static function filterSensitiveData(array $values): array
    {
        $sensitiveFields = ['password', 'ssn', 'credit_card', 'bank_account', 'medical_record'];
        
        foreach ($sensitiveFields as $field) {
            if (array_key_exists($field, $values)) {
                $values[$field] = '[REDACTED]';
            }
        }
        
        return $values;
    }
}