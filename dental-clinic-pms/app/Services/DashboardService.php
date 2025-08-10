<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\InventoryItem;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\User;
use Carbon\Carbon;

class DashboardService
{
    public function getAdministratorData(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'total_patients' => Patient::active()->count(),
            'new_patients_this_month' => Patient::where('created_at', '>=', $thisMonth)->count(),
            'total_appointments_today' => Appointment::today()->count(),
            'upcoming_appointments' => Appointment::upcoming()->count(),
            'revenue_this_month' => Invoice::where('created_at', '>=', $thisMonth)
                ->where('status', 'paid')
                ->sum('total_amount'),
            'outstanding_balance' => Invoice::whereIn('status', ['sent', 'partially_paid', 'overdue'])
                ->sum('outstanding_balance'),
            // Compare stock columns without raw expressions for portability
            'low_stock_items' => InventoryItem::whereColumn('quantity_in_stock', '<=', 'reorder_level')
                ->count(),
            'active_dentists' => User::role('dentist')->active()->count(),
            'active_staff' => User::active()->count(),
            'appointments_by_status' => Appointment::selectRaw('status, COUNT(*) as count')
                ->whereDate('appointment_datetime', '>=', $today)
                ->groupBy('status')
                ->pluck('count', 'status'),
            'revenue_trend' => Invoice::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
                ->where('created_at', '>=', $today->copy()->subDays(30))
                ->where('status', 'paid')
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date'),
            'recent_activities' => $this->getRecentActivities(),
            'todays_appointments' => Appointment::today()->with(['patient', 'dentist'])->orderBy('appointment_datetime')->get(),
            'pending_confirmations' => Appointment::byStatus(Appointment::STATUS_SCHEDULED)->upcoming()->count(),
            'overdue_invoices' => Invoice::where('due_date', '<', $today)->whereIn('status', ['sent', 'partially_paid'])->count(),
            'pending_treatment_plans' => TreatmentPlan::where('status', TreatmentPlan::STATUS_PROPOSED)->with('patient')->orderBy('created_at', 'desc')->take(5)->get(),
        ];
    }

    public function getDentistData(User $dentist): array
    {
        $thisWeek = Carbon::now()->startOfWeek();

        return [
            'todays_appointments' => Appointment::byDentist($dentist->id)
                ->today()
                ->with('patient')
                ->orderBy('appointment_datetime')
                ->get(),
            'active_treatment_plans' => TreatmentPlan::byDentist($dentist->id)
                ->active()
                ->with('patient')
                ->count(),
            'patients_treated_this_week' => Appointment::byDentist($dentist->id)
                ->where('appointment_datetime', '>=', $thisWeek)
                ->where('status', 'completed')
                ->distinct('patient_id')
                ->count(),
            'pending_treatment_plans' => TreatmentPlan::byDentist($dentist->id)
                ->byStatus(TreatmentPlan::STATUS_PROPOSED)
                ->with('patient')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];
    }

    public function getReceptionistData(): array
    {
        $today = Carbon::today();

        return [
            'todays_appointments' => Appointment::today()
                ->with(['patient', 'dentist'])
                ->orderBy('appointment_datetime')
                ->get(),
            'tomorrows_appointments' => Appointment::tomorrow()
                ->with(['patient', 'dentist'])
                ->orderBy('appointment_datetime')
                ->get(),
            'pending_confirmations' => Appointment::byStatus(Appointment::STATUS_SCHEDULED)
                ->upcoming()
                ->with(['patient', 'dentist'])
                ->count(),
            'patients_with_outstanding_balance' => Patient::withOutstandingBalance()->count(),
            'overdue_invoices' => Invoice::where('due_date', '<', $today)
                ->whereIn('status', ['sent', 'partially_paid'])
                ->count(),
            'new_patients_today' => Patient::whereDate('created_at', $today)->count(),
            'recent_registrations' => Patient::orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];
    }

    private function getRecentActivities(): array
    {
        $activities = [];

        $recentPatients = Patient::orderBy('created_at', 'desc')->take(5)->get();
        foreach ($recentPatients as $patient) {
            $activities[] = [
                'type' => 'patient_registered',
                'message' => "New patient registered: {$patient->full_name}",
                'time' => $patient->created_at,
                'icon' => 'user-plus',
                'color' => 'green'
            ];
        }

        $recentAppointments = Appointment::where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->with(['patient', 'dentist'])
            ->take(5)
            ->get();
        foreach ($recentAppointments as $appointment) {
            $activities[] = [
                'type' => 'appointment_completed',
                'message' => "Appointment completed: {$appointment->patient->full_name} with Dr. {$appointment->dentist->name}",
                'time' => $appointment->updated_at,
                'icon' => 'check-circle',
                'color' => 'blue'
            ];
        }

        usort($activities, function($a, $b) {
            return $b['time'] <=> $a['time'];
        });

        return array_slice($activities, 0, 10);
    }

    public function getKpiData(): array
    {
        $today = Carbon::today();

        return [
            'todays_appointments' => Appointment::today()->count(),
            'active_patients' => Patient::active()->count(),
            'daily_revenue' => Invoice::whereDate('created_at', $today)->where('status', 'paid')->sum('total_amount'),
            'pending_payments' => Invoice::whereIn('status', ['sent', 'partially_paid', 'overdue'])->sum('outstanding_balance'),
            'chair_utilization' => '75%', // Mock data for now
        ];
    }

    public function getStaffActivityData()
    {
        $staff = User::role(['dentist', 'receptionist'])->get();
        $statuses = ['Available', 'In Procedure', 'Not Available'];

        foreach ($staff as $member) {
            $member->status = $statuses[array_rand($statuses)];
        }

        return $staff;
    }
}
