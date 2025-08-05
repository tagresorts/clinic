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
        $user = Auth::user();
        $all_widgets = config('dashboard.widgets');
        $user_preferences = UserDashboardPreference::where('user_id', $user->id)->get()->keyBy('widget_key');

        $widgets = [];
        foreach ($all_widgets as $key => $widget) {
            if ($user->can($widget['permission'])) {
                $preference = $user_preferences->get($key);

                $widgets[] = [
                    'key' => $key,
                    'component' => $widget['component'],
                    'layout' => [
                        'x' => $preference->x_pos ?? $widget['default_layout']['x'],
                        'y' => $preference->y_pos ?? $widget['default_layout']['y'],
                        'w' => $preference->width ?? $widget['default_layout']['w'],
                        'h' => $preference->height ?? $widget['default_layout']['h'],
                    ],
                    'visible' => $preference->is_visible ?? true,
                ];
            }
        }

        $role_data = [];
        if ($user->hasRole('administrator')) {
            $role_data = $this->dashboardService->getAdministratorData();
        } elseif ($user->hasRole('dentist')) {
            $role_data = $this->dashboardService->getDentistData($user);
        } elseif ($user->hasRole('receptionist')) {
            $role_data = $this->dashboardService->getReceptionistData();
        }

        return view('dashboard', [
            'widgets' => $widgets,
            'data' => $role_data,
        ]);
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