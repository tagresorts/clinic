<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $kpis = collect(config('dashboard.kpis'))->filter(function ($kpi) use ($user) {
            return $user->can($kpi['permission']);
        });

        $kpiData = $this->dashboardService->getKpiData();

        $staff = [];
        if ($user->can(config('dashboard.panels.staff_activity.permission'))) {
            $staff = $this->dashboardService->getStaffActivityData();
        }

        return view('dashboard-v2', compact('kpis', 'kpiData', 'staff'));
    }

    public function saveLayout(Request $request)
    {
        // This functionality is no longer available
        return response()->json(['success' => false, 'message' => 'Not implemented'], 404);
    }

    public function resetLayout()
    {
        // This functionality is no longer available
        return redirect()->route('dashboard')->with('info', 'Dashboard layout reset is no longer available.');
    }
}