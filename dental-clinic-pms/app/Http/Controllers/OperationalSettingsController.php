<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Services\AuditLogService;

class OperationalSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();
        return view('ops-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'treatment_appointment_reminder_days' => 'required|integer|min:1',
            'stock_digest_reminder_days' => 'required|integer|min:1',
            'expiring_items_reminder_days' => 'required|integer|min:1',
            'expiration_threshold' => 'required|integer|min:1',
        ]);

        $oldSettings = Setting::whereIn('key', array_keys($validated))->pluck('value', 'key')->all();

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        AuditLogService::logFrontendAction(
            'operational_settings_updated',
            null, // No specific model instance, as we are updating multiple settings
            [
                'old_settings' => $oldSettings,
                'new_settings' => $validated,
            ]
        );

        Log::channel('log_viewer')->info("Operational settings updated by " . auth()->user()->name);

        return Redirect::route('ops-settings.index')->with('success', 'Settings updated successfully.');
    }
}
