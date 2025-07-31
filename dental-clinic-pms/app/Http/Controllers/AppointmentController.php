<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'dentist']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by dentist
        if ($request->has('dentist_id') && !empty($request->dentist_id)) {
            $query->where('dentist_id', $request->dentist_id);
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        // For dentists, only show their appointments
        if (auth()->user()->isDentist()) {
            $query->where('dentist_id', auth()->id());
        }

        $appointments = $query->orderBy('appointment_date', 'asc')->paginate(20);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create(Request $request)
    {
        $patient = null;
        if ($request->has('patient_id')) {
            $patient = Patient::findOrFail($request->patient_id);
        }

        $patients = Patient::orderBy('first_name')->get();
        $dentists = User::where('role', 'dentist')->where('is_active', true)->orderBy('name')->get();

        return view('appointments.create', compact('patient', 'patients', 'dentists'));
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:now',
            'appointment_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15|max:480', // 15 minutes to 8 hours
            'type' => 'required|in:consultation,cleaning,extraction,filling,root_canal,crown,whitening,emergency,other',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled,no_show',
        ]);

        // Check for scheduling conflicts
        $conflict = Appointment::where('dentist_id', $validated['dentist_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($validated) {
                $startTime = $validated['appointment_time'];
                $endTime = date('H:i', strtotime($startTime . ' +' . $validated['duration'] . ' minutes'));
                
                $q->where(function ($subQ) use ($startTime, $endTime) {
                    $subQ->where('appointment_time', '<', $endTime)
                         ->whereRaw('TIME_TO_SEC(appointment_time) + (duration * 60) > TIME_TO_SEC(?)', [$startTime]);
                });
            })
            ->first();

        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'This time slot conflicts with an existing appointment.']);
        }

        $appointment = Appointment::create($validated);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment scheduled successfully.');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'dentist', 'treatmentRecords']);

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        // Only allow editing if appointment is not completed
        if ($appointment->status === 'completed') {
            return redirect()->route('appointments.show', $appointment)
                ->with('error', 'Cannot edit completed appointments.');
        }

        $patients = Patient::orderBy('first_name')->get();
        $dentists = User::where('role', 'dentist')->where('is_active', true)->orderBy('name')->get();

        return view('appointments.edit', compact('appointment', 'patients', 'dentists'));
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Only allow updating if appointment is not completed
        if ($appointment->status === 'completed') {
            return redirect()->route('appointments.show', $appointment)
                ->with('error', 'Cannot edit completed appointments.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15|max:480',
            'type' => 'required|in:consultation,cleaning,extraction,filling,root_canal,crown,whitening,emergency,other',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled,no_show',
        ]);

        // Check for scheduling conflicts (excluding current appointment)
        $conflict = Appointment::where('dentist_id', $validated['dentist_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->where('status', '!=', 'cancelled')
            ->where('id', '!=', $appointment->id)
            ->where(function ($q) use ($validated) {
                $startTime = $validated['appointment_time'];
                $endTime = date('H:i', strtotime($startTime . ' +' . $validated['duration'] . ' minutes'));
                
                $q->where(function ($subQ) use ($startTime, $endTime) {
                    $subQ->where('appointment_time', '<', $endTime)
                         ->whereRaw('TIME_TO_SEC(appointment_time) + (duration * 60) > TIME_TO_SEC(?)', [$startTime]);
                });
            })
            ->first();

        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'This time slot conflicts with an existing appointment.']);
        }

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified appointment from storage.
     */
    public function destroy(Appointment $appointment)
    {
        // Only allow deletion if appointment is not completed
        if ($appointment->status === 'completed') {
            return redirect()->route('appointments.show', $appointment)
                ->with('error', 'Cannot delete completed appointments.');
        }

        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Show calendar view of appointments.
     */
    public function calendar(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $month = $request->get('month', now()->format('Y-m'));

        $appointments = Appointment::with(['patient', 'dentist'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($date, $month) {
                $q->whereDate('appointment_date', $date)
                  ->orWhereYear('appointment_date', substr($month, 0, 4))
                  ->whereMonth('appointment_date', substr($month, 5, 2));
            })
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        return view('appointments.calendar', compact('appointments', 'date', 'month'));
    }

    /**
     * Confirm an appointment.
     */
    public function confirm(Appointment $appointment)
    {
        $appointment->update(['status' => 'confirmed']);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment confirmed successfully.');
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(Appointment $appointment)
    {
        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Mark appointment as completed.
     */
    public function complete(Appointment $appointment)
    {
        $appointment->update(['status' => 'completed']);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment marked as completed.');
    }
}
