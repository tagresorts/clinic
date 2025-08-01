<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuditController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index(Request $request)
    {
        // Only administrators can view audit logs
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can view audit logs.');
        }

        $query = AuditLog::with(['user']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('action', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by user
        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action type
        if ($request->has('action') && !empty($request->action)) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by model type
        if ($request->has('model_type') && !empty($request->model_type)) {
            $query->where('model_type', $request->model_type);
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get unique actions for filter dropdown
        $actions = AuditLog::distinct()->pluck('action')->sort()->values();
        
        // Get unique model types for filter dropdown
        $modelTypes = AuditLog::distinct()->pluck('model_type')->sort()->values();

        return view('audit.index', compact('auditLogs', 'actions', 'modelTypes'));
    }

    /**
     * Display the specified audit log.
     */
    public function show(AuditLog $auditLog)
    {
        // Only administrators can view audit logs
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can view audit logs.');
        }

        $auditLog->load('user');

        return view('audit.show', compact('auditLog'));
    }

    /**
     * Export audit logs to CSV.
     */
    public function export(Request $request)
    {
        // Only administrators can export audit logs
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can export audit logs.');
        }

        $query = AuditLog::with(['user']);

        // Apply same filters as index
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('action', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('action') && !empty($request->action)) {
            $query->where('action', $request->action);
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('model_type') && !empty($request->model_type)) {
            $query->where('model_type', $request->model_type);
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'audit_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($auditLogs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'User',
                'Action',
                'Model Type',
                'Model ID',
                'Description',
                'IP Address',
                'User Agent',
                'Created At'
            ]);

            // CSV data
            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : 'System',
                    $log->action,
                    $log->model_type,
                    $log->model_id,
                    $log->description,
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show audit logs for a specific user.
     */
    public function userLogs(Request $request, $userId)
    {
        // Only administrators can view audit logs
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can view audit logs.');
        }

        $query = AuditLog::with(['user'])->where('user_id', $userId);

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(50);

        $user = \App\Models\User::findOrFail($userId);

        return view('audit.user-logs', compact('auditLogs', 'user'));
    }

    /**
     * Show audit logs for a specific model.
     */
    public function modelLogs(Request $request, $modelType, $modelId)
    {
        // Only administrators can view audit logs
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can view audit logs.');
        }

        $query = AuditLog::with(['user'])
            ->where('model_type', $modelType)
            ->where('model_id', $modelId);

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(50);

        return view('audit.model-logs', compact('auditLogs', 'modelType', 'modelId'));
    }
}