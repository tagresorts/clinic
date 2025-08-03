<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'dentist']);

        // Role-based filtering
        if (auth()->user()->isDentist()) {
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

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('appointment_datetime', Carbon::parse($request->date));
        }

        $appointments = $query->orderBy('appointment_datetime', 'desc')->paginate(15);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->isAdministrator() && !auth()->user()->isReceptionist()) {
            abort(403, 'You are not authorized to create appointments.');
        }

        $patients = Patient::where('is_active', true)->orderBy('last_name')->get();
        $dentists = User::where('role', 'dentist')->orderBy('name')->get();

        return view('appointments.create', compact('patients', 'dentists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdministrator() && !auth()->user()->isReceptionist()) {
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

        return redirect()->route('appointments.index')->with('success', 'Appointment scheduled successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        // Authorization: ensure user can view the appointment
        $user = auth()->user();

        if ($user->isDentist() && $appointment->dentist_id !== $user->id) {
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
        if (!auth()->user()->isAdministrator() && !auth()->user()->isReceptionist()) {
            abort(403, 'You are not authorized to edit appointments.');
        }

        $patients = Patient::where('is_active', true)->orderBy('last_name')->get();
        $dentists = User::where('role', 'dentist')->orderBy('name')->get();

        return view('appointments.edit', compact('appointment', 'patients', 'dentists'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        if (!auth()->user()->isAdministrator() && !auth()->user()->isReceptionist()) {
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

        $appointment->addModificationHistory('Updated by receptionist/admin');

        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can delete appointments.');
        }

        // Instead of deleting, we should probably cancel it.
        if ($appointment->status !== Appointment::STATUS_CANCELLED) {
             $appointment->update([
                'status' => Appointment::STATUS_CANCELLED,
                'cancellation_reason' => 'Deleted by administrator.'
             ]);
             $message = 'Appointment cancelled successfully.';
        } else {
            // Or if it's already cancelled, then permanently delete.
            $appointment->delete();
            $message = 'Appointment permanently deleted.';
        }

        return redirect()->route('appointments.index')->with('success', $message);
    }

    /**
     * Display the appointment calendar.
     */
    public function calendar()
    {
        $dentists = User::where('role', 'dentist')->orderBy('name')->get();
        $appointmentStatuses = Appointment::getStatuses();
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

        $query = Appointment::with('patient', 'dentist')
            ->where('status', '!=', Appointment::STATUS_CANCELLED)
            ->whereBetween('appointment_datetime', [$start, $end])
            ->whereHas('patient')
            ->whereHas('dentist');

        // If a specific dentist is requested, filter by them.
        // Otherwise, if the logged-in user is a dentist, only show their appointments.
        if ($request->filled('dentist_id')) {
            $query->byDentist($request->dentist_id);
        } elseif (auth()->user()->isDentist()) {
            $query->byDentist(auth()->id());
        }

        if ($request->filled('appointment_status')) {
            $query->where('status', $request->appointment_status);
        }

        $appointments = $query->get();

        try {
            $events = $appointments->map(function (Appointment $appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->patient->full_name . ' (' . $appointment->appointment_type . ')',
                    'start' => $appointment->appointment_datetime->toIso8601String(),
                    'end' => $appointment->getEndTimeAttribute()->toIso8601String(),
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
            return response()->json([
                'error' => 'An error occurred while processing appointments.',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
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
}
