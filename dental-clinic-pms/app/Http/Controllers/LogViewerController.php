<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LogViewerController extends Controller
{
    protected $logPath;
    protected $availableLogFiles = [];

    public function __construct()
    {
        $this->logPath = storage_path('logs');
        $this->availableLogFiles = $this->getAvailableLogFiles();
    }

    public function index(Request $request)
    {
        try {
            $selectedFile = $request->get('file', 'laravel.log');
            $level = $request->get('level', 'all');
            $search = $request->get('search', '');
            $lines = $request->get('lines', 1000);
            
            // Ensure we have available log files
            if (empty($this->availableLogFiles)) {
                $this->availableLogFiles = $this->getAvailableLogFiles();
            }
            
            $logs = $this->parseLogFile($selectedFile, $level, $search, $lines);
            $logStats = $this->getLogStats($selectedFile);
            
            return view('logs.index', [
                'logs' => $logs,
                'logStats' => $logStats,
                'availableLogFiles' => $this->availableLogFiles,
                'selectedFile' => $selectedFile,
                'level' => $level,
                'search' => $search,
                'lines' => $lines
            ]);
        } catch (\Exception $e) {
            // Log the error and return a fallback view
            \Log::error('Log viewer error: ' . $e->getMessage());
            
            return view('logs.index', [
                'logs' => [],
                'logStats' => ['size' => '0 B', 'lines' => 0, 'modified' => null, 'levels' => []],
                'availableLogFiles' => $this->availableLogFiles ?? [],
                'selectedFile' => 'laravel.log',
                'level' => 'all',
                'search' => '',
                'lines' => 1000,
                'error' => 'An error occurred while loading logs. Please check the application logs for details.'
            ]);
        }
    }

    public function download($filename)
    {
        $filePath = storage_path("logs/{$filename}");
        
        if (!File::exists($filePath)) {
            abort(404, 'Log file not found');
        }

        return response()->download($filePath);
    }

    public function clear($filename)
    {
        $filePath = storage_path("logs/{$filename}");
        
        if (!File::exists($filePath)) {
            return back()->with('error', 'Log file not found');
        }

        // Clear the log file
        File::put($filePath, '');
        
        return back()->with('success', "Log file {$filename} has been cleared");
    }

    public function delete($filename)
    {
        $filePath = storage_path("logs/{$filename}");
        
        if (!File::exists($filePath)) {
            return back()->with('error', 'Log file not found');
        }

        // Delete the log file
        File::delete($filePath);
        
        return back()->with('success', "Log file {$filename} has been deleted");
    }

    protected function getAvailableLogFiles(): array
    {
        $files = [];
        
        if (File::exists($this->logPath)) {
            $logFiles = File::files($this->logPath);
            
            foreach ($logFiles as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'log') {
                    $filename = pathinfo($file, PATHINFO_BASENAME);
                    $files[] = [
                        'name' => $filename,
                        'size' => $this->formatBytes(File::size($file)),
                        'modified' => File::lastModified($file),
                        'path' => $file
                    ];
                }
            }
        }

        // If no log files found, create a default laravel.log if it doesn't exist
        if (empty($files)) {
            $defaultLogPath = storage_path('logs/laravel.log');
            if (!File::exists($defaultLogPath)) {
                File::put($defaultLogPath, '[' . now()->format('Y-m-d H:i:s') . '] local.INFO: Log viewer initialized. No existing logs found.');
            }
            
            // Re-check for files after creating default
            if (File::exists($this->logPath)) {
                $logFiles = File::files($this->logPath);
                foreach ($logFiles as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'log') {
                        $filename = pathinfo($file, PATHINFO_BASENAME);
                        $files[] = [
                            'name' => $filename,
                            'size' => $this->formatBytes(File::size($file)),
                            'modified' => File::lastModified($file),
                            'path' => $file
                        ];
                    }
                }
            }
        }

        // Sort by modification time (newest first)
        usort($files, function($a, $b) {
            return $b['modified'] <=> $a['modified'];
        });

        return $files;
    }

    protected function parseLogFile($filename, $level = 'all', $search = '', $maxLines = 1000): array
    {
        $filePath = storage_path("logs/{$filename}");
        
        if (!File::exists($filePath)) {
            return [];
        }

        $content = File::get($filePath);
        $lines = explode("\n", $content);
        
        // Reverse to get newest lines first
        $lines = array_reverse($lines);
        
        $parsedLogs = [];
        $lineCount = 0;

        foreach ($lines as $line) {
            if ($lineCount >= $maxLines) {
                break;
            }

            if (empty(trim($line))) {
                continue;
            }

            $logEntry = $this->parseLogLine($line);
            
            if ($logEntry) {
                // Apply level filter
                if ($level !== 'all' && $logEntry['level'] !== $level) {
                    continue;
                }

                // Apply search filter
                if ($search && !str_contains(strtolower($logEntry['message']), strtolower($search))) {
                    continue;
                }

                $parsedLogs[] = $logEntry;
                $lineCount++;
            }
        }

        return $parsedLogs;
    }

    protected function parseLogLine($line): ?array
    {
        // Laravel log format: [2024-01-01 00:00:00] local.ERROR: message
        if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.+)$/', $line, $matches)) {
            return [
                'timestamp' => $matches[1],
                'environment' => $matches[2],
                'level' => strtolower($matches[3]),
                'message' => $matches[4],
                'raw' => $line
            ];
        }

        // Alternative format: [2024-01-01 00:00:00] level: message
        if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+): (.+)$/', $line, $matches)) {
            return [
                'timestamp' => $matches[1],
                'environment' => 'local',
                'level' => strtolower($matches[2]),
                'message' => $matches[3],
                'raw' => $line
            ];
        }

        // Fallback for other formats
        return [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'environment' => 'unknown',
            'level' => 'info',
            'message' => $line,
            'raw' => $line
        ];
    }

    protected function getLogStats($filename): array
    {
        $filePath = storage_path("logs/{$filename}");
        
        if (!File::exists($filePath)) {
            return [
                'size' => '0 B',
                'lines' => 0,
                'modified' => null,
                'levels' => []
            ];
        }

        $content = File::get($filePath);
        $lines = explode("\n", $content);
        $levels = [];

        foreach ($lines as $line) {
            $logEntry = $this->parseLogLine($line);
            if ($logEntry) {
                $level = $logEntry['level'];
                $levels[$level] = ($levels[$level] ?? 0) + 1;
            }
        }

        return [
            'size' => $this->formatBytes(File::size($filePath)),
            'lines' => count(array_filter($lines)),
            'modified' => File::lastModified($filePath),
            'levels' => $levels
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
}
