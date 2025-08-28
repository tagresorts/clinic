<?php

namespace App\Http\Controllers;

use App\Models\SmtpConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
        
        Log::channel('log_viewer')->info("SMTP configuration '{$config->name}' created by " . auth()->user()->name, [
            'config_id' => $config->id,
            'host' => $config->host,
            'port' => $config->port,
            'encryption' => $config->encryption,
            'has_username' => !empty($config->username),
            'has_password' => !empty($config->password),
            'from_email' => $config->from_email
        ]);
        
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
        
        $oldName = $smtp->name;
        $oldHost = $smtp->host;
        $oldPort = $smtp->port;
        
        if (!empty($validated['password'])) {
            $validated['password'] = Crypt::encryptString($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $smtp->update($validated);
        
        Log::channel('log_viewer')->info("SMTP configuration '{$oldName}' updated by " . auth()->user()->name, [
            'config_id' => $smtp->id,
            'old_name' => $oldName,
            'new_name' => $validated['name'],
            'old_host' => $oldHost,
            'new_host' => $validated['host'],
            'old_port' => $oldPort,
            'new_port' => $validated['port'],
            'password_changed' => isset($validated['password'])
        ]);
        
        return redirect()->route('smtp.index')->with('success', 'SMTP configuration updated.');
    }

    public function destroy(SmtpConfig $smtp)
    {
        $configName = $smtp->name;
        $configId = $smtp->id;
        
        $smtp->delete();
        
        Log::channel('log_viewer')->info("SMTP configuration '{$configName}' deleted by " . auth()->user()->name, [
            'config_id' => $configId
        ]);
        
        return redirect()->route('smtp.index')->with('success', 'SMTP configuration deleted.');
    }

    public function setDefault(SmtpConfig $smtp)
    {
        SmtpConfig::query()->update(['is_default' => false]);
        $smtp->update(['is_default' => true]);
        
        Log::channel('log_viewer')->info("Default SMTP configuration changed to '{$smtp->name}' by " . auth()->user()->name, [
            'config_id' => $smtp->id
        ]);
        
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

            Log::channel('log_viewer')->info("SMTP test email sent successfully by " . auth()->user()->name, [
                'config_id' => $smtp->id,
                'config_name' => $smtp->name,
                'test_recipient' => $to
            ]);

            return back()->with('success', 'Test email sent successfully!');
        } catch (\Exception $e) {
            Log::channel('log_viewer')->error("SMTP test email failed by " . auth()->user()->name, [
                'config_id' => $smtp->id,
                'config_name' => $smtp->name,
                'test_recipient' => $to,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
