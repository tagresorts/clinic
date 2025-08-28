<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'dentist']);

        // Role-based filtering
        if (auth()->user()->hasRole('dentist')) {
            $query->where('dentist_id', auth()->id());
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('patient', function ($q2) use ($searchTerm) {
                    $q2->where('first_name', 'like', "%{$searchTerm}%")
                       ->orWhere('last_name', 'like', "%{$searchTerm}%");
                })->orWhereHas('dentist', function ($q3) use ($searchTerm) {
                    $q3->where('name', 'like', "%{$searchTerm}%");
                });
            });
        }

        // Filter by date range
        if ($request->has('date_range') && !empty($request->date_range)) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate = Carbon::parse($dates[1])->endOfDay();
                $query->whereBetween('appointment_datetime', [$startDate, $endDate]);
            }
        }

        $appointments = $query->orderBy('appointment_datetime', 'desc')->paginate(15);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->hasRole(['administrator', 'receptionist'])) {
            abort(403, 'You are not authorized to create appointments.');
        }

        $patients = Patient::orderBy('last_name')->get();
        $dentists = User::role('dentist')->orderBy('name')->get();

        return view('appointments.create', compact('patients', 'dentists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole(['administrator', 'receptionist'])) {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'duration_minutes' => 'required|integer|min:15',
            'appointment_type' => 'required|string|max:255',
            'reason_for_visit' => 'nullable|string',
            'appointment_notes' => 'nullable|string',
        ]);

        $datetime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        // Check for conflicts
        if (Appointment::hasConflict($validated['dentist_id'], $datetime, $validated['duration_minutes'])) {
            return redirect()->back()->withInput()->with('error', 'The selected dentist has a conflicting appointment at that time.');
        }

        Appointment::create([
            'patient_id' => $validated['patient_id'],
            'dentist_id' => $validated['dentist_id'],
            'appointment_datetime' => $datetime,
            'duration_minutes' => $validated['duration_minutes'],
            'appointment_type' => $validated['appointment_type'],
            'reason_for_visit' => $validated['reason_for_visit'] ?? null,
            'appointment_notes' => $validated['appointment_notes'] ?? null,
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        Log::channel('log_viewer')->info("Appointment scheduled by " . auth()->user()->name, [
            'patient_id' => $validated['patient_id'],
            'dentist_id' => $validated['dentist_id'],
            'appointment_datetime' => $datetime->toDateTimeString(),
            'duration_minutes' => $validated['duration_minutes'],
            'appointment_type' => $validated['appointment_type'],
            'scheduled_by_role' => auth()->user()->roles->first()->name ?? 'unknown'
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment scheduled successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        // Authorization: ensure user can view the appointment
        $user = auth()->user();

        if ($user->hasRole('dentist') && $appointment->dentist_id !== $user->id) {
            abort(403, 'This appointment is not for one of your patients.');
        }

        // Receptionists and Admins can see any appointment.

        $appointment->load(['patient', 'dentist', 'treatmentRecords']);

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        if (!auth()->user()->hasRole(['administrator', 'receptionist'])) {
            abort(403, 'You are not authorized to edit appointments.');
        }

        $patients = Patient::orderBy('last_name')->get();
        $dentists = User::role('dentist')->orderBy('name')->get();

        return view('appointments.edit', compact('appointment', 'patients', 'dentists'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        if (!auth()->user()->hasRole(['administrator', 'receptionist'])) {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'duration_minutes' => 'required|integer|min:15',
            'appointment_type' => 'required|string|max:255',
            'status' => 'required|in:' . implode(',', Appointment::getStatuses()),
            'reason_for_visit' => 'nullable|string',
            'appointment_notes' => 'nullable|string',
            'cancellation_reason' => 'nullable|string',
        ]);

        $datetime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        // Check for conflicts, excluding the current appointment
        if (Appointment::hasConflict($validated['dentist_id'], $datetime, $validated['duration_minutes'], $appointment->id)) {
            return redirect()->back()->withInput()->with('error', 'The selected dentist has a conflicting appointment at that time.');
        }

        $appointment->update([
            'patient_id' => $validated['patient_id'],
            'dentist_id' => $validated['dentist_id'],
            'appointment_datetime' => $datetime,
            'duration_minutes' => $validated['duration_minutes'],
            'appointment_type' => $validated['appointment_type'],
            'status' => $validated['status'],
            'reason_for_visit' => $validated['reason_for_visit'] ?? null,
            'appointment_notes' => $validated['appointment_notes'] ?? null,
            'cancellation_reason' => $validated['cancellation_reason'] ?? null,
        ]);

        Log::channel('log_viewer')->info("Appointment updated by " . auth()->user()->name, [
            'appointment_id' => $appointment->id,
            'patient_id' => $validated['patient_id'],
            'dentist_id' => $validated['dentist_id'],
            'appointment_datetime' => $datetime->toDateTimeString(),
            'status' => $validated['status'],
            'updated_by_role' => auth()->user()->roles->first()->name ?? 'unknown'
        ]);

        $appointment->addModificationHistory('Updated by receptionist/admin');

        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        if (!auth()->user()->hasRole('administrator')) {
            abort(403, 'Only administrators can delete appointments.');
        }

        // Instead of deleting, we should probably cancel it.
        if ($appointment->status !== Appointment::STATUS_CANCELLED) {
            $appointment->update([
                'status' => Appointment::STATUS_CANCELLED,
                'cancellation_reason' => 'Deleted by administrator.'
             ]);
             $message = 'Appointment cancelled successfully.';
             
             Log::channel('log_viewer')->info("Appointment cancelled by administrator " . auth()->user()->name, [
                 'appointment_id' => $appointment->id,
                 'patient_id' => $appointment->patient_id,
                 'dentist_id' => $appointment->dentist_id,
                 'cancellation_reason' => 'Deleted by administrator.'
             ]);
        } else {
            // Or if it's already cancelled, then permanently delete.
            $appointment->delete();
            $message = 'Appointment permanently deleted.';
            
            Log::channel('log_viewer')->info("Appointment permanently deleted by administrator " . auth()->user()->name, [
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'dentist_id' => $appointment->dentist_id
            ]);
        }

        return redirect()->route('appointments.index')->with('success', $message);
    }

    /**
     * Display the appointment calendar.
     */
    public function calendar()
    {
        $dentists = User::role('dentist')->orderBy('name')->get();
        $appointmentStatuses = [
            Appointment::STATUS_SCHEDULED,
            Appointment::STATUS_CONFIRMED,
            Appointment::STATUS_CANCELLED,
            Appointment::STATUS_COMPLETED,
            Appointment::STATUS_NO_SHOW,
        ];
        return view('appointments.calendar', compact('dentists', 'appointmentStatuses'));
    }

    /**
     * Provide appointment data for the calendar feed.
     */
    public function feed(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $start = Carbon::parse($request->start)->startOfDay();
        $end = Carbon::parse($request->end)->endOfDay();

        $query = $this->getAppointmentsQuery($request);
        $appointments = $query->whereBetween('appointment_datetime', [$start, $end])->get();

        try {
            $events = $appointments->map(function (Appointment $appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->patient->full_name . ' (' . $appointment->appointment_type . ')',
                    'start' => $appointment->appointment_datetime->toIso8601String(),
                    'end' => $appointment->end_time->toIso8601String(),
                    'url' => route('appointments.show', $appointment),
                    'backgroundColor' => $this->getStatusColor($appointment->status),
                    'borderColor' => $this->getStatusColor($appointment->status),
                    'extendedProps' => [
                        'dentist' => 'Dr. ' . $appointment->dentist->name,
                        'status' => ucfirst(str_replace('_', ' ', $appointment->status)),
                        'reason' => $appointment->reason_for_visit,
                    ]
                ];
            });
            return response()->json($events);
        } catch (\Throwable $e) {
            Log::error('Calendar feed error', [
                'exception' => $e,
            ]);
            return response()->json([
                'error' => 'Unable to load appointments at this time.'
            ], 500);
        }
    }

    /**
     * Get a color based on appointment status.
     */
    private function getStatusColor(string $status): string
    {
        return match ($status) {
            Appointment::STATUS_SCHEDULED => '#3b82f6', // blue-500
            Appointment::STATUS_CONFIRMED => '#10b981', // green-500
            Appointment::STATUS_IN_PROGRESS => '#f97316', // orange-500
            Appointment::STATUS_COMPLETED => '#6b7280', // gray-500
            Appointment::STATUS_CANCELLED => '#ef4444', // red-500
            Appointment::STATUS_NO_SHOW => '#eab308', // yellow-500
            default => '#6b7280',
        };
    }

    /**
     * Build the base query for appointments with filters.
     */
    private function getAppointmentsQuery(Request $request)
    {
        $query = Appointment::with('patient', 'dentist');

        // If a specific dentist is requested, filter by them.
        // Otherwise, if the logged-in user is a dentist, only show their appointments.
        if ($request->filled('dentist_id')) {
            $query->byDentist($request->dentist_id);
        } elseif (auth()->user()->hasRole('dentist')) {
            $query->byDentist(auth()->id());
        }

        if ($request->filled('appointment_status')) {
            $query->where('status', $request->appointment_status);
        }

        return $query;
    }

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

        $query = $this->getAppointmentsQuery($request);
        Log::info('Summary Query Start: ' . $start->toDateTimeString());
        Log::info('Summary Query End: ' . $end->toDateTimeString());
        Log::info('Summary Query SQL: ' . $query->toSql());
        Log::info('Summary Query Bindings: ' . json_encode($query->getBindings()));
        $appointments = $query->whereBetween('appointment_datetime', [$start, $end])
                              ->orderBy('appointment_datetime')
                              ->get();
        Log::info('Appointments retrieved for summary: ' . $appointments->count());

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

    public function checkConflict(Request $request)
    {
        $validated = $request->validate([
            'dentist_id' => 'required|exists:users,id',
            'appointment_datetime' => 'required|date',
            'duration_minutes' => 'required|integer|min:15',
            'exclude_id' => 'nullable|exists:appointments,id',
        ]);

        $hasConflict = Appointment::hasConflict(
            $validated['dentist_id'],
            new \Carbon\Carbon($validated['appointment_datetime']),
            $validated['duration_minutes'],
            $validated['exclude_id'] ?? null
        );

        return response()->json(['has_conflict' => $hasConflict]);
    }

    public function tentative()
    {
        $dentistId = auth()->id();
        $appointments = Appointment::with('patient')
            ->where('dentist_id', $dentistId)
            ->where('status', Appointment::STATUS_TENTATIVE)
            ->orderBy('appointment_datetime')
            ->paginate(15);

        return view('appointments.tentative', compact('appointments'));
    }

    /**
     * Confirm a tentative appointment.
     */
    public function confirm(Appointment $appointment)
    {
        if (!auth()->user()->hasRole(['administrator', 'receptionist', 'dentist'])) {
            abort(403, 'You are not authorized to confirm appointments.');
        }

        $oldStatus = $appointment->status;
        $appointment->update(['status' => Appointment::STATUS_CONFIRMED]);

        Log::channel('log_viewer')->info("Appointment confirmed by " . auth()->user()->name, [
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'dentist_id' => $appointment->dentist_id,
            'old_status' => $oldStatus,
            'new_status' => Appointment::STATUS_CONFIRMED,
            'confirmed_by_role' => auth()->user()->roles->first()->name ?? 'unknown'
        ]);

        return redirect()->back()->with('success', 'Appointment confirmed successfully.');
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        if (!auth()->user()->hasRole(['administrator', 'receptionist', 'dentist'])) {
            abort(403, 'You are not authorized to cancel appointments.');
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $oldStatus = $appointment->status;
        $appointment->update([
            'status' => Appointment::STATUS_CANCELLED,
            'cancellation_reason' => $request->cancellation_reason ?? 'Cancelled by user.'
        ]);

        Log::channel('log_viewer')->info("Appointment cancelled by " . auth()->user()->name, [
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'dentist_id' => $appointment->dentist_id,
            'old_status' => $oldStatus,
            'new_status' => Appointment::STATUS_CANCELLED,
            'cancellation_reason' => $request->cancellation_reason ?? 'Cancelled by user.',
            'cancelled_by_role' => auth()->user()->roles->first()->name ?? 'unknown'
        ]);

        return redirect()->back()->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Complete an appointment.
     */
    public function complete(Appointment $appointment)
    {
        if (!auth()->user()->hasRole(['administrator', 'dentist'])) {
            abort(403, 'You are not authorized to complete appointments.');
        }

        $oldStatus = $appointment->status;
        $appointment->update(['status' => Appointment::STATUS_COMPLETED]);

        Log::channel('log_viewer')->info("Appointment completed by " . auth()->user()->name, [
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'dentist_id' => $appointment->dentist_id,
            'old_status' => $oldStatus,
            'new_status' => Appointment::STATUS_COMPLETED,
            'completed_by_role' => auth()->user()->roles->first()->name ?? 'unknown'
        ]);

        return redirect()->back()->with('success', 'Appointment completed successfully.');
    }
}
