<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return Redirect::route('ops-settings.index')->with('success', 'Settings updated successfully.');
    }
}
