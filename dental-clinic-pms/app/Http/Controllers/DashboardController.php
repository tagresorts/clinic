<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use App\Models\UserDashboardPreference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $user = Auth::user();

        // Aggregate dashboard data across services; role-aware where applicable
        $data = [];
        try { $data = array_merge($data, $this->dashboardService->getAdministratorData()); } catch (\Throwable $e) { /* ignore */ }
        try { if ($user->hasRole('dentist')) { $data = array_merge($data, $this->dashboardService->getDentistData($user)); } } catch (\Throwable $e) { /* ignore */ }
        try { $data = array_merge($data, $this->dashboardService->getReceptionistData()); } catch (\Throwable $e) { /* ignore */ }

        // Widgets: merge config defaults with user preferences; hide widgets marked invisible
        $widgetDefinitions = config('dashboard.widgets');
        $preferences = UserDashboardPreference::where('user_id', $user->id)->get()->keyBy('widget_key');

        $widgets = [];
        foreach ($widgetDefinitions as $key => $definition) {
            $isVisible = true;
            $layout = $definition['default_layout'];

            if (isset($preferences[$key])) {
                $pref = $preferences[$key];
                $isVisible = (bool)$pref->is_visible;
                $layout = [
                    'x' => (int)$pref->x_pos,
                    'y' => (int)$pref->y_pos,
                    'w' => (int)$pref->width,
                    'h' => (int)$pref->height,
                ];
            }

            if ($isVisible) {
                $widgets[] = [
                    'key' => $key,
                    'component' => $definition['component'],
                    'layout' => $layout,
                ];
            }
        }

        // Sort widgets for initial render order (top-left first)
        usort($widgets, function ($a, $b) {
            return ($a['layout']['y'] <=> $b['layout']['y']) ?: ($a['layout']['x'] <=> $b['layout']['x']);
        });

        return view('dashboard', [
            'widgets' => $widgets,
            'data' => $data,
        ]);
    }

    /**
     * Return KPI panel HTML partial for dynamic refresh.
     */
    public function kpisHtml(Request $request)
    {
        $kpis = config('dashboard.kpis');
        $timeframe = $request->get('timeframe', 'today');
        $kpiData = $this->dashboardService->getKpiData($timeframe);

        return view('dashboard._kpi_panel', compact('kpis', 'kpiData'));
    }

    /**
     * Return KPI data as JSON for API-style consumption.
     */
    public function kpisJson(Request $request)
    {
        $timeframe = $request->get('timeframe', 'today');
        $kpiData = $this->dashboardService->getKpiData($timeframe);
        return response()->json($kpiData);
    }

    /**
     * Get saved layout for current user.
     */
    public function getLayout()
    {
        $user = Auth::user();
        $prefs = UserDashboardPreference::where('user_id', $user->id)
            ->get(['widget_key as id', 'x_pos as x', 'y_pos as y', 'width as w', 'height as h', 'is_visible']);

        if ($prefs->isEmpty()) {
            // Role-based defaults
            if ($user->hasRole('dentist')) {
                $defaults = [
                    ['id' => 'kpi', 'x' => 0, 'y' => 0, 'w' => 6, 'h' => 2, 'is_visible' => true],
                    ['id' => 'appointments', 'x' => 0, 'y' => 2, 'w' => 8, 'h' => 6, 'is_visible' => true],
                    ['id' => 'patient', 'x' => 8, 'y' => 2, 'w' => 4, 'h' => 3, 'is_visible' => true],
                    ['id' => 'alerts', 'x' => 8, 'y' => 5, 'w' => 4, 'h' => 3, 'is_visible' => true],
                    ['id' => 'mini-report', 'x' => 0, 'y' => 8, 'w' => 6, 'h' => 3, 'is_visible' => true],
                ];
            } elseif ($user->hasRole('administrator')) {
                $defaults = [
                    ['id' => 'kpi', 'x' => 0, 'y' => 0, 'w' => 6, 'h' => 2, 'is_visible' => true],
                    ['id' => 'alerts', 'x' => 6, 'y' => 0, 'w' => 6, 'h' => 2, 'is_visible' => true],
                    ['id' => 'appointments', 'x' => 0, 'y' => 2, 'w' => 7, 'h' => 5, 'is_visible' => true],
                    ['id' => 'patient', 'x' => 7, 'y' => 2, 'w' => 5, 'h' => 3, 'is_visible' => true],
                    ['id' => 'admin-notices', 'x' => 7, 'y' => 5, 'w' => 5, 'h' => 2, 'is_visible' => true],
                    ['id' => 'mini-report', 'x' => 0, 'y' => 7, 'w' => 6, 'h' => 3, 'is_visible' => true],
                ];
            } else {
                // Receptionist/staff default
                $defaults = [
                    ['id' => 'kpi', 'x' => 0, 'y' => 0, 'w' => 6, 'h' => 2, 'is_visible' => true],
                    ['id' => 'appointments', 'x' => 0, 'y' => 2, 'w' => 8, 'h' => 6, 'is_visible' => true],
                    ['id' => 'patient', 'x' => 8, 'y' => 2, 'w' => 4, 'h' => 4, 'is_visible' => true],
                    ['id' => 'mini-report', 'x' => 0, 'y' => 8, 'w' => 6, 'h' => 3, 'is_visible' => true],
                ];
            }
            return response()->json($defaults);
        }

        return response()->json($prefs);
    }

    /**
     * Save widget visibility per user.
     */
    public function saveWidgetVisibility(Request $request)
    {
        $validated = $request->validate([
            'widgets' => 'required|array',
            'widgets.*.id' => 'required|string',
            'widgets.*.is_visible' => 'required|boolean',
        ]);

        $user = Auth::user();
        foreach ($validated['widgets'] as $widget) {
            UserDashboardPreference::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'widget_key' => $widget['id'],
                ],
                [
                    // Preserve layout if exists, else set sensible defaults
                    'x_pos' => UserDashboardPreference::where('user_id', $user->id)->where('widget_key', $widget['id'])->value('x_pos') ?? 0,
                    'y_pos' => UserDashboardPreference::where('user_id', $user->id)->where('widget_key', $widget['id'])->value('y_pos') ?? 0,
                    'width' => UserDashboardPreference::where('user_id', $user->id)->where('widget_key', $widget['id'])->value('width') ?? 4,
                    'height' => UserDashboardPreference::where('user_id', $user->id)->where('widget_key', $widget['id'])->value('height') ?? 2,
                    'is_visible' => $widget['is_visible'],
                ]
            );
        }

        return response()->json(['success' => true]);
    }

    /**
     * Alerts for dashboard widgets.
     */
    public function alertsJson()
    {
        $alerts = [];
        try {
            // Low stock items
            $lowStockCount = \App\Models\InventoryItem::whereColumn('quantity_in_stock', '<=', 'reorder_level')->count();
            if ($lowStockCount > 0) {
                $alerts[] = [
                    'type' => 'inventory',
                    'level' => 'warning',
                    'message' => "$lowStockCount items are at or below reorder level",
                ];
            }
            // Expiring items
            $expiringCount = \App\Models\InventoryItem::where('has_expiry', true)
                ->whereDate('expiry_date', '<=', Carbon\Carbon::today()->addDays(30))
                ->count();
            if ($expiringCount > 0) {
                $alerts[] = [
                    'type' => 'inventory',
                    'level' => 'info',
                    'message' => "$expiringCount items expiring within 30 days",
                ];
            }
            // Late appointments (scheduled in past 30 mins not completed/cancelled)
            $lateCount = \App\Models\Appointment::where('appointment_datetime', '<', Carbon\Carbon::now()->subMinutes(30))
                ->whereIn('status', ['scheduled','confirmed'])
                ->count();
            if ($lateCount > 0) {
                $alerts[] = [
                    'type' => 'appointments',
                    'level' => 'danger',
                    'message' => "$lateCount appointments may be running late",
                ];
            }
        } catch (\Throwable $e) {
            $alerts[] = [ 'type' => 'system', 'level' => 'error', 'message' => 'Unable to load alerts at this time.' ];
        }
        return response()->json($alerts);
    }

    /**
     * Mini report: appointments per day over last 7 days.
     */
    public function miniReportAppointments()
    {
        $start = Carbon\Carbon::today()->subDays(6);
        $data = \App\Models\Appointment::selectRaw('DATE(appointment_datetime) as date, COUNT(*) as count')
            ->where('appointment_datetime', '>=', $start)
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');
        return response()->json($data);
    }

    public function saveLayout(Request $request)
    {
        $request->validate([
            'layout' => 'required|array',
        ]);

        $user = Auth::user();
        foreach ($request->layout as $item) {
            UserDashboardPreference::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'widget_key' => $item['id'],
                ],
                [
                    'x_pos' => $item['x'],
                    'y_pos' => $item['y'],
                    'width' => $item['w'],
                    'height' => $item['h'],
                    'is_visible' => true, // Assuming visible if it's in the layout
                ]
            );
        }

        Log::channel('log_viewer')->info("Dashboard layout saved by " . $user->name, [
            'user_id' => $user->id,
            'widgets_count' => count($request->layout),
            'action' => 'save_layout'
        ]);

        return response()->json(['success' => true]);
    }

    public function resetLayout()
    {
        $user = Auth::user();
        $deletedCount = UserDashboardPreference::where('user_id', $user->id)->delete();
        
        Log::channel('log_viewer')->info("Dashboard layout reset by " . $user->name, [
            'user_id' => $user->id,
            'deleted_preferences_count' => $deletedCount,
            'action' => 'reset_layout'
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Dashboard layout has been reset to default.');
    }
}