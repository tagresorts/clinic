<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class ExpirationThresholdController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();
        return view('expiration_threshold.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expiration_threshold' => 'required|integer|min:1',
        ]);

        Setting::updateOrCreate(
            ['key' => 'expiration_threshold'],
            ['value' => $validated['expiration_threshold']]
        );

        return redirect()->route('expiration_threshold.index')->with('success', 'Expiration threshold updated successfully.');
    }
}
