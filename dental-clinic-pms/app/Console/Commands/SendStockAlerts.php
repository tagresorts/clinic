<?php

namespace App\Console\Commands;

use App\Mail\StockDigestMail;
use App\Models\InventoryItem;
use App\Models\Setting;
use App\Models\EmailTemplate;
use App\Models\SmtpConfig;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportFactory;

class SendStockAlerts extends Command
{
    protected $signature = 'stock:check-alerts';
    protected $description = 'Send digest email for low stock and expiring items';

    public function handle(): int
    {
        $expirationThreshold = Setting::where('key', 'expiration_threshold')->first()->value ?? 30;
        $today = Carbon::today();

        $low = InventoryItem::whereColumn('quantity_in_stock', '<=', 'reorder_level')->get(['item_name','quantity_in_stock','reorder_level'])->toArray();
        $expiring = InventoryItem::where('has_expiry', true)->whereDate('expiry_date', '<=', $today->copy()->addDays($expirationThreshold))->get(['item_name','expiry_date'])->toArray();

        if (empty($low) && empty($expiring)) {
            $this->info('No stock alerts to send.');
            return self::SUCCESS;
        }

        $recipients = $this->getRecipientsFromTemplateOrDefault();
        if (empty($recipients)) {
            $this->warn('No recipients configured for stock alerts.');
            return self::SUCCESS;
        }

        $this->withSmtp(function () use ($recipients, $low, $expiring) {
            foreach ($recipients as $email) {
                Mail::to($email)->queue(new StockDigestMail($low, $expiring));
            }
        });

        $this->info('Stock digest queued to '.count($recipients).' recipient(s).');
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
        $envList = array_filter(array_map('trim', explode(',', (string)env('ALERT_STOCK_RECIPIENTS', ''))));
        if ($envList) return $envList;
        return User::role('administrator')->pluck('email')->all();
    }

    private function withSmtp(callable $callback): void
    {
        $cfg = SmtpConfig::where('is_default', true)->where('is_active', true)->first();
        if (!$cfg) { $callback(); return; }

        $password = $cfg->password ? Crypt::decryptString($cfg->password) : null;
        $dsn = new Dsn('smtp', $cfg->host, $cfg->username, $password, $cfg->port, $cfg->encryption ?: null);
        $transport = (new TransportFactory())->fromDsnObject($dsn);
        $symfonyMailer = new SymfonyMailer($transport);

        $laravelMailer = new \Illuminate\Mail\Mailer('stock', app('view'), $symfonyMailer);
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
