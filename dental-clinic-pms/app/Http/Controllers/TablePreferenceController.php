<?php

namespace App\Http\Controllers;

use App\Models\UserTablePreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TablePreferenceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_key' => 'required|string|max:255',
            'preferences' => 'required|array',
        ]);

        $preference = UserTablePreference::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'table_key' => $validated['table_key'],
            ],
            [
                'preferences' => $validated['preferences'],
            ]
        );

        return response()->json(['success' => true, 'data' => $preference->preferences]);
    }
}
