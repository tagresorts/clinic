<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();
        Log::channel('log_viewer')->info("User logged in", [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'login_method' => 'web_form',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            Log::channel('log_viewer')->info("User logged out", [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'logout_method' => 'web_form',
                'ip_address' => $request->ip()
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
