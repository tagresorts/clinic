<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function saveLayout(Request $request)
    {
        // This functionality is no longer available
        return response()->json(['success' => false, 'message' => 'Not implemented'], 404);
    }

    public function resetLayout()
    {
        // This functionality is no longer available
        return redirect()->route('dashboard')->with('info', 'Dashboard layout reset is no longer available.');
    }
}