<?php

namespace App\Http\Controllers;

use App\Models\SmtpConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class SmtpConfigController extends Controller
{
    public function index()
    {
        $configs = SmtpConfig::orderByDesc('is_default')->orderBy('name')->get();
        return view('smtp.index', compact('configs'));
    }

    public function create()
    {
        return view('smtp.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1',
            'encryption' => 'nullable|in:tls,ssl',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:2048',
            'from_email' => 'nullable|email',
            'from_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
        $validated['password'] = $validated['password'] ? Crypt::encryptString($validated['password']) : null;
        $config = SmtpConfig::create($validated);
        return redirect()->route('smtp.index')->with('success', 'SMTP configuration added.');
    }

    public function edit(SmtpConfig $smtp)
    {
        return view('smtp.edit', compact('smtp'));
    }

    public function update(Request $request, SmtpConfig $smtp)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1',
            'encryption' => 'nullable|in:tls,ssl',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:2048',
            'from_email' => 'nullable|email',
            'from_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
        if (!empty($validated['password'])) {
            $validated['password'] = Crypt::encryptString($validated['password']);
        } else {
            unset($validated['password']);
        }
        $smtp->update($validated);
        return redirect()->route('smtp.index')->with('success', 'SMTP configuration updated.');
    }

    public function destroy(SmtpConfig $smtp)
    {
        $smtp->delete();
        return redirect()->route('smtp.index')->with('success', 'SMTP configuration deleted.');
    }

    public function setDefault(SmtpConfig $smtp)
    {
        SmtpConfig::query()->update(['is_default' => false]);
        $smtp->update(['is_default' => true]);
        return redirect()->route('smtp.index')->with('success', 'Default SMTP updated.');
    }

    public function testSend(Request $request, SmtpConfig $smtp)
    {
        $request->validate(['to' => 'required|email']);
        $to = $request->input('to');

        try {
            $password = $smtp->password ? Crypt::decryptString($smtp->password) : null;

            $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
                $smtp->host,
                $smtp->port,
                $smtp->encryption === 'ssl'
            );
            $transport->setUsername($smtp->username);
            $transport->setPassword($password);

            $mailer = new \Illuminate\Mail\Mailer('custom', app('view'), $transport);
            $mailer->alwaysFrom($smtp->from_email ?: config('mail.from.address'), $smtp->from_name ?: config('mail.from.name'));

            $mailer->raw('This is a test email to verify your SMTP configuration.', function ($message) use ($to) {
                $message->to($to)->subject('SMTP Configuration Test');
            });

            return back()->with('success', 'Test email sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
