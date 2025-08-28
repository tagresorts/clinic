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
        $kpis = config('dashboard.kpis');
        $kpiData = $this->dashboardService->getKpiData();
        $staff = $this->dashboardService->getStaffActivityData();
        $patientData = $this->dashboardService->getReceptionistData();
        $upcomingAppointments = $this->dashboardService->getUpcomingAppointmentsForDashboard(Auth::user());

        return view('dashboard-v3', compact('kpis', 'kpiData', 'staff', 'patientData', 'upcomingAppointments'));
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
            ->get(['widget_key', 'x_pos as x', 'y_pos as y', 'width as w', 'height as h']);
        return response()->json($prefs);
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