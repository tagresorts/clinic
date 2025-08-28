<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CleanupLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:cleanup 
                            {--days=14 : Number of days to keep logs}
                            {--compress : Compress old log files}
                            {--force : Force cleanup without confirmation}
                            {--dry-run : Show what would be cleaned without actually doing it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old log files and optionally compress them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $compress = $this->option('compress');
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        $logPath = storage_path('logs');
        
        if (!File::exists($logPath)) {
            $this->error('Logs directory does not exist: ' . $logPath);
            return 1;
        }

        $this->info("Log cleanup process started...");
        $this->info("Logs directory: {$logPath}");
        $this->info("Keeping logs for: {$days} days");
        $this->info("Compression: " . ($compress ? 'Enabled' : 'Disabled'));
        $this->info("Mode: " . ($dryRun ? 'Dry Run' : 'Live'));

        $cutoffDate = now()->subDays($days);
        $logFiles = $this->getLogFiles($logPath);
        
        $filesToProcess = [];
        $totalSize = 0;

        foreach ($logFiles as $file) {
            $lastModified = File::lastModified($file);
            $fileDate = \Carbon\Carbon::createFromTimestamp($lastModified);
            
            if ($fileDate->lt($cutoffDate)) {
                $size = File::size($file);
                $filesToProcess[] = [
                    'path' => $file,
                    'name' => basename($file),
                    'size' => $size,
                    'modified' => $fileDate,
                    'size_formatted' => $this->formatBytes($size)
                ];
                $totalSize += $size;
            }
        }

        if (empty($filesToProcess)) {
            $this->info("No old log files found to clean up.");
            return 0;
        }

        $this->info("\nFiles to be processed:");
        $this->table(
            ['Filename', 'Size', 'Last Modified', 'Age (days)'],
            array_map(function($file) use ($cutoffDate) {
                return [
                    $file['name'],
                    $file['size_formatted'],
                    $file['modified']->format('Y-m-d H:i:s'),
                    $file['modified']->diffInDays($cutoffDate)
                ];
            }, $filesToProcess)
        );

        $this->info("Total files: " . count($filesToProcess));
        $this->info("Total size: " . $this->formatBytes($totalSize));

        if (!$force && !$dryRun) {
            if (!$this->confirm('Do you want to proceed with the cleanup?')) {
                $this->info('Cleanup cancelled.');
                return 0;
            }
        }

        if ($dryRun) {
            $this->info("\nDRY RUN - No files were actually modified.");
            return 0;
        }

        $this->info("\nStarting cleanup process...");
        
        $processed = 0;
        $errors = 0;

        foreach ($filesToProcess as $file) {
            try {
                if ($compress) {
                    $this->compressLogFile($file['path']);
                    $this->line("✓ Compressed: {$file['name']}");
                } else {
                    File::delete($file['path']);
                    $this->line("✓ Deleted: {$file['name']}");
                }
                $processed++;
            } catch (\Exception $e) {
                $this->error("✗ Error processing {$file['name']}: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("\nCleanup completed!");
        $this->info("Successfully processed: {$processed} files");
        
        if ($errors > 0) {
            $this->warn("Errors encountered: {$errors} files");
        }

        // Show remaining disk space
        $this->showDiskUsage($logPath);

        return 0;
    }

    protected function getLogFiles($path): array
    {
        $files = [];
        
        if (File::exists($path)) {
            $logFiles = File::files($path);
            
            foreach ($logFiles as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'log') {
                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    protected function compressLogFile($filePath): void
    {
        $content = File::get($filePath);
        $compressed = gzencode($content, 9);
        
        $compressedPath = $filePath . '.gz';
        File::put($compressedPath, $compressed);
        
        // Delete original file after successful compression
        File::delete($filePath);
    }

    protected function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    protected function showDiskUsage($logPath): void
    {
        $totalSize = 0;
        $fileCount = 0;
        
        if (File::exists($logPath)) {
            $files = File::files($logPath);
            
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'log' || pathinfo($file, PATHINFO_EXTENSION) === 'gz') {
                    $totalSize += File::size($file);
                    $fileCount++;
                }
            }
        }

        $this->info("\nCurrent log storage:");
        $this->info("Files: {$fileCount}");
        $this->info("Total size: " . $this->formatBytes($totalSize));
    }
}