<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Provide a summary of appointments for a given date range.
     */
    public function summary(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $start = Carbon::parse($request->start_date)->startOfDay();
        $end = Carbon::parse($request->end_date)->endOfDay();

        $query = Appointment::with('patient', 'dentist')
            ->where('status', '!=', Appointment::STATUS_CANCELLED)
            ->whereBetween('appointment_datetime', [$start, $end])
            ->whereHas('patient')
            ->whereHas('dentist');

        if (auth()->user()->isDentist()) {
            $query->byDentist(auth()->id());
        }

        $appointments = $query->orderBy('appointment_datetime')->get();

        // We can return the appointments directly, or format them.
        // For the summary, a simple format is probably best.
        $formattedAppointments = $appointments->map(function ($appointment) {
            return [
                'time' => $appointment->appointment_datetime->format('g:i A'),
                'patient_name' => $appointment->patient->full_name,
                'dentist_name' => 'Dr. ' . $appointment->dentist->name,
                'type' => $appointment->appointment_type,
                'status' => ucfirst(str_replace('_', ' ', $appointment->status)),
                'url' => route('appointments.show', $appointment),
            ];
        });

        return response()->json($formattedAppointments);
    }
}
