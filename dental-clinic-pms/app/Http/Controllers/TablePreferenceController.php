<?php

namespace App\Http\Controllers;

use App\Models\UserTablePreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TablePreferenceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_key' => 'required|string|max:255',
            'preferences' => 'required|array',
        ]);

        $user = Auth::user();
        $preference = UserTablePreference::updateOrCreate(
            [
                'user_id' => $user->id,
                'table_key' => $validated['table_key'],
            ],
            [
                'preferences' => $validated['preferences'],
            ]
        );

        Log::channel('log_viewer')->info("Table preferences updated by " . $user->name, [
            'user_id' => $user->id,
            'table_key' => $validated['table_key'],
            'preferences_count' => count($validated['preferences']),
            'action' => 'update_preferences'
        ]);

        return response()->json(['success' => true, 'data' => $preference->preferences]);
    }
}
