<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use App\Models\UserDashboardPreference;
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
        $kpis = config('dashboard.kpis');
        $kpiData = $this->dashboardService->getKpiData();
        $staff = $this->dashboardService->getStaffActivityData();
        $patientData = $this->dashboardService->getReceptionistData();

        return view('dashboard-v3', compact('kpis', 'kpiData', 'staff', 'patientData'));
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

        return response()->json(['success' => true]);
    }

    public function resetLayout()
    {
        UserDashboardPreference::where('user_id', Auth::id())->delete();
        return redirect()->route('dashboard')->with('success', 'Dashboard layout has been reset to default.');
    }
}