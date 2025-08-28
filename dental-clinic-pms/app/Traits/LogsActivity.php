<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            static::logActivity($model, 'created');
        });

        static::updated(function (Model $model) {
            static::logActivity($model, 'updated');
        });

        static::deleted(function (Model $model) {
            static::logActivity($model, 'deleted');
        });
    }

    protected static function logActivity(Model $model, string $action)
    {
        AuditLog::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'user_name' => Auth::check() ? Auth::user()->name : null,
            'user_role' => Auth::check() ? Auth::user()->getRoleNames()->first() : null,
            'action' => $action,
            'entity_type' => get_class($model),
            'entity_id' => $model->id,
            'entity_description' => static::getEntityDescription($model),
            'description' => static::getEntityDescription($model),
            'old_values' => $action === 'updated' ? json_encode($model->getOriginal()) : null,
            'new_values' => $action !== 'deleted' ? json_encode($model->getAttributes()) : null,
            'changed_fields' => $action === 'updated' ? json_encode($model->getChanges()) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'request_method' => request()->method(),
            'request_url' => request()->fullUrl(),
            'event_time' => now(),
        ]);
    }

    protected static function getEntityDescription(Model $model): string
    {
        if (property_exists($model, 'name')) {
            return $model->name;
        }

        if (property_exists($model, 'title')) {
            return $model->title;
        }

        return get_class($model) . ' #' . $model->id;
    }
}
