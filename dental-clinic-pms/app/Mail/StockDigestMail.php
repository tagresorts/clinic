<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

class StockDigestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $lowStock;
    public array $expiring;

    public function __construct(array $lowStock, array $expiring)
    {
        $this->lowStock = $lowStock;
        $this->expiring = $expiring;
    }

    public function build()
    {
        $lowCount = count($this->lowStock);
        $expiringCount = count($this->expiring);

        $template = EmailTemplate::where('type', 'stock_digest')->first();
        if ($template) {
            $lowTable = $this->renderLowStockTable($this->lowStock);
            $expTable = $this->renderExpiringTable($this->expiring);
            $inventoryUrl = route('inventory.index');

            $replacements = [
                '{{low_stock_table}}' => $lowTable,
                '{{expiring_stock_table}}' => $expTable,
                '{{inventory_url}}' => $inventoryUrl,
                '{{low_count}}' => (string) $lowCount,
                '{{expiring_count}}' => (string) $expiringCount,
            ];

            $subject = strtr($template->subject, $replacements);
            $body = strtr($template->body, $replacements);

            return $this->subject($subject)
                ->view('emails.custom', ['body' => $body]);
        }

        // Fallback legacy rendering
        return $this->subject('Stock Alerts: Low / Expiring Items')
            ->view('emails.stock_digest');
    }

    private function renderLowStockTable(array $items): string
    {
        if (count($items) === 0) return '';
        $rows = '';
        foreach ($items as $i) {
            $rows .= '<tr>'
                . '<td>' . e($i['item_name']) . '</td>'
                . '<td>' . e($i['quantity_in_stock']) . '</td>'
                . '<td>' . e($i['reorder_level']) . '</td>'
                . '</tr>';
        }
        return '<h3>Low Stock</h3>'
            . '<table border="1" cellpadding="6" cellspacing="0" width="100%">'
            . '<tr><th align="left">Item</th><th align="left">Qty</th><th align="left">Reorder</th></tr>'
            . $rows
            . '</table>';
    }

    private function renderExpiringTable(array $items): string
    {
        if (count($items) === 0) return '';
        $rows = '';
        foreach ($items as $i) {
            $date = \Carbon\Carbon::parse($i['expiry_date'])->format('M d, Y');
            $rows .= '<tr>'
                . '<td>' . e($i['item_name']) . '</td>'
                . '<td>' . e($date) . '</td>'
                . '</tr>';
        }
        return '<h3>Expiring Soon</h3>'
            . '<table border="1" cellpadding="6" cellspacing="0" width="100%">'
            . '<tr><th align="left">Item</th><th align="left">Expiry Date</th></tr>'
            . $rows
            . '</table>';
    }
}
