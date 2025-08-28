<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index(Request $request)
    {
        try {
            // Check if the audit_logs table exists and has the right structure
            if (!Schema::hasTable('audit_logs')) {
                throw new \Exception('Audit logs table does not exist. Please run migrations.');
            }
            
            $query = AuditLog::query();

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by entity type
        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->entity_type);
        }

        // Filter by severity
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('event_time', '>=', Carbon::parse($request->date_from)->startOfDay());
        }
        if ($request->filled('date_to')) {
            $query->where('event_time', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('user_name', 'like', '%' . $request->search . '%')
                  ->orWhere('entity_description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by requires review
        if ($request->filled('requires_review')) {
            $query->where('requires_review', $request->boolean('requires_review'));
        }

        // Get available filter options
        $users = AuditLog::distinct()->pluck('user_name', 'user_id')->filter();
        $actions = AuditLog::distinct()->pluck('action')->filter();
        $entityTypes = AuditLog::distinct()->pluck('entity_type')->filter();
        $severities = ['low', 'medium', 'high'];

        // Get statistics
        $stats = $this->getAuditStats();

        // Paginate results
        $auditLogs = $query->orderBy('event_time', 'desc')->paginate(50);

        return view('audit-logs.index', compact(
            'auditLogs',
            'users',
            'actions',
            'entityTypes',
            'severities',
            'stats'
        ));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Audit log index error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a simple error view
            return view('audit-logs.error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Display the specified audit log.
     */
    public function show(AuditLog $auditLog)
    {
        return view('audit-logs.show', compact('auditLog'));
    }

    /**
     * Export audit logs to CSV.
     */
    public function export(Request $request)
    {
        $query = AuditLog::query();

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->entity_type);
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('date_from')) {
            $query->where('event_time', '>=', Carbon::parse($request->date_from)->startOfDay());
        }
        if ($request->filled('date_to')) {
            $query->where('event_time', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        $auditLogs = $query->orderBy('event_time', 'desc')->get();

        $filename = 'audit-logs-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($auditLogs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'User', 'Role', 'Action', 'Entity Type', 'Entity Description',
                'IP Address', 'User Agent', 'Request Method', 'Request URL',
                'Description', 'Severity', 'Event Time', 'Requires Review',
                'Session ID', 'Metadata'
            ]);

            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user_name,
                    $log->user_role,
                    $log->action,
                    $log->entity_type,
                    $log->entity_description,
                    $log->ip_address,
                    $log->user_agent,
                    $log->request_method,
                    $log->request_url,
                    $log->description,
                    $log->severity,
                    $log->event_time,
                    $log->requires_review ? 'Yes' : 'No',
                    $log->session_id,
                    json_encode($log->metadata)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get audit statistics.
     */
    private function getAuditStats()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'total_logs' => AuditLog::count(),
            'today_logs' => AuditLog::where('event_time', '>=', $today)->count(),
            'week_logs' => AuditLog::where('event_time', '>=', $thisWeek)->count(),
            'month_logs' => AuditLog::where('event_time', '>=', $thisMonth)->count(),
            'high_severity' => AuditLog::where('severity', 'high')->count(),
            'requires_review' => AuditLog::where('requires_review', true)->count(),
            'top_actions' => AuditLog::select('action', DB::raw('count(*) as count'))
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
            'top_users' => AuditLog::select('user_name', DB::raw('count(*) as count'))
                ->groupBy('user_name')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Mark audit log as reviewed.
     */
    public function markReviewed(AuditLog $auditLog)
    {
        $auditLog->update(['requires_review' => false]);

        return redirect()->back()->with('success', 'Audit log marked as reviewed.');
    }

    /**
     * Mark audit log as requiring review.
     */
    public function markForReview(AuditLog $auditLog)
    {
        $auditLog->update(['requires_review' => true]);

        return redirect()->back()->with('success', 'Audit log marked for review.');
    }

    /**
     * Get audit log timeline for a specific entity.
     */
    public function entityTimeline(Request $request)
    {
        $request->validate([
            'entity_type' => 'required|string',
            'entity_id' => 'required|integer'
        ]);

        $logs = AuditLog::where('entity_type', $request->entity_type)
            ->where('entity_id', $request->entity_id)
            ->orderBy('event_time', 'desc')
            ->get();

        return response()->json($logs);
    }

    /**
     * Get user activity timeline.
     */
    public function userTimeline(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'days' => 'integer|min:1|max:30'
        ]);

        $days = $request->get('days', 7);
        $startDate = Carbon::now()->subDays($days);

        $logs = AuditLog::where('user_id', $request->user_id)
            ->where('event_time', '>=', $startDate)
            ->orderBy('event_time', 'desc')
            ->get();

        return response()->json($logs);
    }
}