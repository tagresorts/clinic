<?php

namespace App\Console\Commands;

use App\Mail\StockDigestMail;
use App\Models\InventoryItem;
use App\Models\Setting;
use App\Models\EmailTemplate;
use App\Models\SmtpConfig;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;

class SendLowStockAlerts extends Command
{
    protected $signature = 'stock:send-low-stock-alerts';
    protected $description = 'Send digest email for low stock items';

    public function handle(): int
    {
        $reminderDays = Setting::where('key', 'stock_digest_reminder_days')->first()->value;

        $low = InventoryItem::whereColumn('quantity_in_stock', '<=', 'reorder_level')->get(['item_name','quantity_in_stock','reorder_level'])->toArray();

        if (empty($low)) {
            $this->info('No low stock alerts to send.');
            return self::SUCCESS;
        }

        $recipients = $this->getRecipientsFromTemplateOrDefault();
        if (empty($recipients)) {
            $this->warn('No recipients configured for stock alerts.');
            return self::SUCCESS;
        }

        $this->withSmtp(function () use ($recipients, $low) {
            foreach ($recipients as $email) {
                Mail::to($email)->queue(new StockDigestMail($low, []));
            }
        });

        $this->info('Low stock digest queued to '.count($recipients).' recipient(s).');
        return self::SUCCESS;
    }

    private function getRecipientsFromTemplateOrDefault(): array
    {
        // Try template-specific recipients for stock_digest
        $tpl = EmailTemplate::where('type', 'stock_digest')->first();
        if ($tpl && $tpl->recipient_type) {
            if ($tpl->recipient_type === 'manual' && $tpl->recipient_emails) {
                $manual = array_filter(array_map('trim', explode(',', $tpl->recipient_emails)));
                if ($manual) return $manual;
            }
            if ($tpl->recipient_type === 'roles' && $tpl->recipient_roles) {
                $roles = array_filter(array_map('trim', explode(',', $tpl->recipient_roles)));
                if ($roles) {
                    return User::role($roles)->pluck('email')->unique()->values()->all();
                }
            }
        }
        // Fallback to env or admins
        $envList = array_filter(array_map('trim', explode(',', (string)config('mail.stock_recipients', ''))));
        if ($envList) return $envList;
        return User::role('administrator')->pluck('email')->all();
    }

    private function withSmtp(callable $callback): void
    {
        $cfg = SmtpConfig::where('is_default', true)->where('is_active', true)->first();
        if (!$cfg) { $callback(); return; }

        $password = $cfg->password ? Crypt::decryptString($cfg->password) : null;
        $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
            $cfg->host,
            $cfg->port,
            $cfg->encryption === 'ssl'
        );
        if ($cfg->username) { $transport->setUsername($cfg->username); }
        if ($password) { $transport->setPassword($password); }
        $laravelMailer = new \Illuminate\Mail\Mailer('stock', app('view'), $transport, app('events'));
        $laravelMailer->alwaysFrom($cfg->from_email ?: config('mail.from.address'), $cfg->from_name ?: config('mail.from.name'));
        $laravelMailer->setQueue(app('queue'));

        $original = Mail::getFacadeRoot();
        Mail::setFacadeApplication(app());
        app()->instance('mail.manager', new class($laravelMailer) extends \Illuminate\Mail\MailManager { public function __construct($mailer){ $this->app = app(); $this->setDefaultDriver('stock'); $this->mailers = ['stock' => $mailer]; } });
        Mail::alwaysFrom($cfg->from_email ?: config('mail.from.address'), $cfg->from_name ?: config('mail.from.name'));

        try { $callback(); } finally {
            // Let Laravel resolve default transport again next time (no persistent override here)
        }
    }
}
