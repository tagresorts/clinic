<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $user->sendEmailVerificationNotification();

        Log::channel('log_viewer')->info("Email verification notification sent", [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'notification_method' => 'web_request',
            'ip_address' => $request->ip()
        ]);

        return back()->with('status', 'verification-link-sent');
    }
}
