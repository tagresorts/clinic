<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Models\InventoryItem;
use App\Models\Setting;
use App\Models\SmtpConfig;
use Carbon\Carbon;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('email_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('email_templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|max:255|unique:email_templates,type',
            'recipient_type' => 'nullable|in:manual,roles',
            'recipient_emails' => 'nullable|string',
            'recipient_roles' => 'nullable|string',
        ]);

        EmailTemplate::create($validated);

        return redirect()->route('email_templates.index')->with('success', 'Email template created successfully.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('email_templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|max:255|unique:email_templates,type,' . $emailTemplate->id,
            'recipient_type' => 'nullable|in:manual,roles',
            'recipient_emails' => 'nullable|string',
            'recipient_roles' => 'nullable|string',
        ]);

        $emailTemplate->update($validated);

        return redirect()->route('email_templates.index')->with('success', 'Email template updated successfully.');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();

        return redirect()->route('email_templates.index')->with('success', 'Email template deleted successfully.');
    }

    public function test(Request $request, EmailTemplate $emailTemplate)
    {
        $data = $request->validate([
            'to' => 'required|email',
        ]);

        try {
            [$subject, $body] = $this->renderForTest($emailTemplate);

            $this->withSmtp(function () use ($data, $subject, $body) {
                Mail::send('emails.custom', ['body' => $body], function ($message) use ($data, $subject) {
                    $message->to($data['to'])
                        ->subject($subject);
                });
            });

            return back()->with('success', 'Test email sent to '.$data['to']);
        } catch (\Throwable $e) {
            Log::error('Email template test failed', [
                'template_id' => $emailTemplate->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Failed to send test: '.$e->getMessage());
        }
    }

    private function renderForTest(EmailTemplate $template): array
    {
        $type = $template->type;
        $replacements = [];

        if ($type === 'password_reset') {
            $userName = auth()->user()->name ?? 'Test User';
            $resetLink = url(route('password.reset', [
                'token' => 'test-token',
                'email' => auth()->user()->email ?? 'test@example.com',
            ], false));
            $replacements = [
                '{{user_name}}' => $userName,
                '{{reset_link}}' => $resetLink,
            ];
        } elseif (in_array($type, ['stock_digest', 'stock_expiring'])) {
            $today = Carbon::today();
            $expirationThreshold = Setting::where('key', 'expiration_threshold')->first()->value ?? 30;
            $low = InventoryItem::whereColumn('quantity_in_stock', '<=', 'reorder_level')
                ->limit(5)->get(['item_name','quantity_in_stock','reorder_level'])->toArray();
            $expiring = InventoryItem::where('has_expiry', true)
                ->whereDate('expiry_date', '<=', $today->copy()->addDays($expirationThreshold))
                ->limit(5)->get(['item_name','expiry_date'])->toArray();

            $lowTable = $this->renderLowTable($low);
            $expTable = $this->renderExpTable($expiring);
            $replacements = [
                '{{low_stock_table}}' => $lowTable,
                '{{expiring_stock_table}}' => $expTable,
                '{{inventory_url}}' => route('inventory.index'),
                '{{low_count}}' => (string) count($low),
                '{{expiring_count}}' => (string) count($expiring),
            ];
        }

        $subject = strtr($template->subject, $replacements);
        $body = strtr($template->body, $replacements);
        return [$subject, $body];
    }

    private function renderLowTable(array $items): string
    {
        if (!$items) return '';
        $rows = '';
        foreach ($items as $i) {
            $rows .= '<tr><td>'.e($i['item_name']).'</td><td>'.e($i['quantity_in_stock']).'</td><td>'.e($i['reorder_level']).'</td></tr>';
        }
        return '<h3>Low Stock</h3><table border="1" cellpadding="6" cellspacing="0" width="100%">'
            .'<tr><th align="left">Item</th><th align="left">Qty</th><th align="left">Reorder</th></tr>'
            .$rows
            .'</table>';
    }

    private function renderExpTable(array $items): string
    {
        if (!$items) return '';
        $rows = '';
        foreach ($items as $i) {
            $date = Carbon::parse($i['expiry_date'])->format('M d, Y');
            $rows .= '<tr><td>'.e($i['item_name']).'</td><td>'.e($date).'</td></tr>';
        }
        return '<h3>Expiring Soon</h3><table border="1" cellpadding="6" cellspacing="0" width="100%">'
            .'<tr><th align="left">Item</th><th align="left">Expiry Date</th></tr>'
            .$rows
            .'</table>';
    }

    /**
     * Use the app's default active SMTP config for the duration of the callback if available.
     */
    private function withSmtp(callable $callback): void
    {
        $cfg = SmtpConfig::where('is_default', true)->where('is_active', true)->first();
        if (!$cfg) { $callback(); return; }

        $password = $cfg->password ? Crypt::decryptString($cfg->password) : null;
        $dsn = new \Symfony\Component\Mailer\Transport\Dsn('smtp', $cfg->host, $cfg->username, $password, $cfg->port, $cfg->encryption ?: null);
        $transport = (new \Symfony\Component\Mailer\Transport\TransportFactory())->fromDsnObject($dsn);
        $symfonyMailer = new \Symfony\Component\Mailer\Mailer($transport);

        $laravelMailer = new \Illuminate\Mail\Mailer('smtp-test', app('view'), $symfonyMailer);
        $laravelMailer->alwaysFrom($cfg->from_email ?: config('mail.from.address'), $cfg->from_name ?: config('mail.from.name'));
        $laravelMailer->setQueue(app('queue'));

        // Swap Mail manager just for this call
        $original = Mail::getFacadeRoot();
        app()->instance('mail.manager', new class($laravelMailer) extends \Illuminate\Mail\MailManager { public function __construct($mailer){ $this->app = app(); $this->setDefaultDriver('smtp-test'); $this->mailers = ['smtp-test' => $mailer]; } });
        Mail::alwaysFrom($cfg->from_email ?: config('mail.from.address'), $cfg->from_name ?: config('mail.from.name'));

        try { $callback(); } finally {
            // No persistent override; next mail resolution will use default config again
        }
    }
}
