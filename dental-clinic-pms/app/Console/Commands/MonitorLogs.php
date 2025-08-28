<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class MonitorLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:monitor 
                            {--alert-size=100 : Alert if any log file exceeds this size in MB}
                            {--alert-total=500 : Alert if total logs exceed this size in MB}
                            {--email= : Email address to send alerts to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor log file sizes and disk usage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $alertSize = $this->option('alert-size') * 1024 * 1024; // Convert MB to bytes
        $alertTotal = $this->option('alert-total') * 1024 * 1024; // Convert MB to bytes
        $email = $this->option('email');

        $logPath = storage_path('logs');
        
        if (!File::exists($logPath)) {
            $this->error('Logs directory does not exist: ' . $logPath);
            return 1;
        }

        $this->info("Log monitoring started...");
        $this->info("Logs directory: {$logPath}");
        $this->info("Size alert threshold: " . $this->formatBytes($alertSize));
        $this->info("Total size alert threshold: " . $this->formatBytes($alertTotal));

        $logFiles = $this->getLogFiles($logPath);
        $totalSize = 0;
        $alerts = [];

        $this->info("\nLog file analysis:");
        $this->line(str_repeat('-', 80));

        foreach ($logFiles as $file) {
            $size = File::size($file);
            $totalSize += $size;
            $lastModified = File::lastModified($file);
            $age = now()->diffInDays(\Carbon\Carbon::createFromTimestamp($lastModified));

            $status = '';
            if ($size > $alertSize) {
                $status = '⚠️  LARGE';
                $alerts[] = "Large log file: " . basename($file) . " (" . $this->formatBytes($size) . ")";
            } elseif ($age > 30) {
                $status = '📅 OLD';
            } else {
                $status = '✅ OK';
            }

            $this->line(sprintf(
                "%-40s %-15s %-15s %-10s %s",
                basename($file),
                $this->formatBytes($size),
                \Carbon\Carbon::createFromTimestamp($lastModified)->format('Y-m-d'),
                $age . ' days',
                $status
            ));
        }

        $this->line(str_repeat('-', 80));
        $this->info("Total size: " . $this->formatBytes($totalSize));
        $this->info("Total files: " . count($logFiles));

        // Check total size threshold
        if ($totalSize > $alertTotal) {
            $alerts[] = "Total log size exceeds threshold: " . $this->formatBytes($totalSize);
        }

        // Check disk space
        $diskUsage = $this->getDiskUsage($logPath);
        $this->info("\nDisk usage:");
        $this->info("Available: " . $this->formatBytes($diskUsage['available']));
        $this->info("Used: " . $this->formatBytes($diskUsage['used']));
        $this->info("Total: " . $this->formatBytes($diskUsage['total']));
        $this->info("Usage: " . round($diskUsage['usage_percent'], 1) . "%");

        if ($diskUsage['usage_percent'] > 90) {
            $alerts[] = "Disk usage is high: " . round($diskUsage['usage_percent'], 1) . "%";
        }

        // Show recommendations
        if (!empty($alerts)) {
            $this->warn("\n⚠️  ALERTS:");
            foreach ($alerts as $alert) {
                $this->warn("  • " . $alert);
            }

            $this->info("\n💡 RECOMMENDATIONS:");
            $this->info("  • Run: php artisan logs:cleanup --days=7");
            $this->info("  • Run: php artisan logs:cleanup --compress");
            $this->info("  • Check for log rotation configuration");
            $this->info("  • Consider increasing disk space if usage is consistently high");

            // Send email alert if configured
            if ($email && !empty($alerts)) {
                $this->sendEmailAlert($email, $alerts, $totalSize, $diskUsage);
            }
        } else {
            $this->info("\n✅ All log files are within acceptable limits.");
        }

        return 0;
    }

    protected function getLogFiles($path): array
    {
        $files = [];
        
        if (File::exists($path)) {
            $logFiles = File::files($path);
            
            foreach ($logFiles as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'log' || pathinfo($file, PATHINFO_EXTENSION) === 'gz') {
                    $files[] = $file;
                }
            }
        }

        // Sort by size (largest first)
        usort($files, function($a, $b) {
            return File::size($b) <=> File::size($a);
        });

        return $files;
    }

    protected function getDiskUsage($path): array
    {
        $total = disk_total_space($path);
        $free = disk_free_space($path);
        $used = $total - $free;
        $usagePercent = ($used / $total) * 100;

        return [
            'total' => $total,
            'used' => $used,
            'free' => $free,
            'available' => $free,
            'usage_percent' => $usagePercent
        ];
    }

    protected function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    protected function sendEmailAlert($email, $alerts, $totalSize, $diskUsage): void
    {
        try {
            $subject = 'Log Monitoring Alert - ' . config('app.name');
            
            $body = "Log monitoring has detected the following issues:\n\n";
            $body .= "ALERTS:\n";
            foreach ($alerts as $alert) {
                $body .= "• " . $alert . "\n";
            }
            
            $body .= "\nCURRENT STATUS:\n";
            $body .= "Total log size: " . $this->formatBytes($totalSize) . "\n";
            $body .= "Disk usage: " . round($diskUsage['usage_percent'], 1) . "%\n";
            $body .= "Available space: " . $this->formatBytes($diskUsage['available']) . "\n";
            
            $body .= "\nRECOMMENDED ACTIONS:\n";
            $body .= "• Run: php artisan logs:cleanup --days=7\n";
            $body .= "• Run: php artisan logs:cleanup --compress\n";
            $body .= "• Check server disk space\n";
            
            $body .= "\nThis alert was generated at: " . now()->format('Y-m-d H:i:s');

            // Simple mail sending (you can enhance this with proper mail classes)
            mail($email, $subject, $body);
            
            $this->info("Email alert sent to: {$email}");
        } catch (\Exception $e) {
            $this->error("Failed to send email alert: " . $e->getMessage());
        }
    }
}