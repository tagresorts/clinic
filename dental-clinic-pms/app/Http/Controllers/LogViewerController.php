<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogViewerController extends Controller
{
    public function index()
    {
        $logFile = storage_path('logs/log_viewer.log');
        $logs = [];

        if (file_exists($logFile)) {
            $logs = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }

        return view('logs.index', ['logs' => $logs]);
    }
}
