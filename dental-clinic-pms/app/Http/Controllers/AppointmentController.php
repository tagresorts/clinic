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
            'reason_for_visit' => $validated['reason_for_visit'],
            'appointment_notes' => $validated['appointment_notes'],
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
            'reason_for_visit' => $validated['reason_for_visit'],
            'appointment_notes' => $validated['appointment_notes'],
            'cancellation_reason' => $validated['cancellation_reason'],
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
}
