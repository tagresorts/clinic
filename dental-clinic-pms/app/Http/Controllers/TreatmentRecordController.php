<?php

namespace App\Http\Controllers;

use App\Models\TreatmentRecord;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TreatmentRecordController extends Controller
{
    /**
     * Display a listing of treatment records.
     */
    public function index(Request $request)
    {
        $query = TreatmentRecord::with(['patient', 'dentist', 'appointment']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by dentist
        if ($request->has('dentist_id') && !empty($request->dentist_id)) {
            $query->where('dentist_id', $request->dentist_id);
        }

        // Filter by treatment type
        if ($request->has('treatment_type') && !empty($request->treatment_type)) {
            $query->where('treatment_type', $request->treatment_type);
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('treatment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('treatment_date', '<=', $request->date_to);
        }

        // For dentists, only show their treatment records
        if (auth()->user()->isDentist()) {
            $query->where('dentist_id', auth()->id());
        }

        $treatmentRecords = $query->orderBy('treatment_date', 'desc')->paginate(20);

        return view('treatment-records.index', compact('treatmentRecords'));
    }

    /**
     * Show the form for creating a new treatment record.
     */
    public function create(Request $request)
    {
        // Only dentists and administrators can create treatment records
        if (!auth()->user()->isDentist() && !auth()->user()->isAdministrator()) {
            abort(403, 'Only dentists and administrators can create treatment records.');
        }

        $patient = null;
        $appointment = null;

        if ($request->has('patient_id')) {
            $patient = Patient::findOrFail($request->patient_id);
        }

        if ($request->has('appointment_id')) {
            $appointment = Appointment::with('patient')->findOrFail($request->appointment_id);
            $patient = $appointment->patient;
        }

        $patients = Patient::orderBy('first_name')->get();
        $appointments = Appointment::with('patient')
            ->where('status', 'completed')
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('treatment-records.create', compact('patient', 'appointment', 'patients', 'appointments'));
    }

    /**
     * Store a newly created treatment record in storage.
     */
    public function store(Request $request)
    {
        // Only dentists and administrators can create treatment records
        if (!auth()->user()->isDentist() && !auth()->user()->isAdministrator()) {
            abort(403, 'Only dentists and administrators can create treatment records.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'treatment_type' => 'required|string|max:255',
            'treatment_date' => 'required|date|before_or_equal:today',
            'teeth_involved' => 'nullable|string|max:255',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'procedure_performed' => 'required|string',
            'materials_used' => 'nullable|string',
            'anesthesia_used' => 'nullable|string|max:255',
            'complications' => 'nullable|string',
            'post_treatment_instructions' => 'nullable|string',
            'next_appointment_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ]);

        // Set dentist_id to current user if they are a dentist
        if (auth()->user()->isDentist()) {
            $validated['dentist_id'] = auth()->id();
        } else {
            $validated['dentist_id'] = $request->dentist_id ?? auth()->id();
        }

        $treatmentRecord = TreatmentRecord::create($validated);

        return redirect()->route('treatment-records.show', $treatmentRecord)
            ->with('success', 'Treatment record created successfully.');
    }

    /**
     * Display the specified treatment record.
     */
    public function show(TreatmentRecord $treatmentRecord)
    {
        $treatmentRecord->load(['patient', 'dentist', 'appointment']);

        return view('treatment-records.show', compact('treatmentRecord'));
    }

    /**
     * Show the form for editing the specified treatment record.
     */
    public function edit(TreatmentRecord $treatmentRecord)
    {
        // Only the treating dentist or administrators can edit treatment records
        if (!auth()->user()->isAdministrator() && 
            (!auth()->user()->isDentist() || $treatmentRecord->dentist_id !== auth()->id())) {
            abort(403, 'You can only edit your own treatment records.');
        }

        $patients = Patient::orderBy('first_name')->get();
        $appointments = Appointment::with('patient')
            ->where('status', 'completed')
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('treatment-records.edit', compact('treatmentRecord', 'patients', 'appointments'));
    }

    /**
     * Update the specified treatment record in storage.
     */
    public function update(Request $request, TreatmentRecord $treatmentRecord)
    {
        // Only the treating dentist or administrators can edit treatment records
        if (!auth()->user()->isAdministrator() && 
            (!auth()->user()->isDentist() || $treatmentRecord->dentist_id !== auth()->id())) {
            abort(403, 'You can only edit your own treatment records.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'treatment_type' => 'required|string|max:255',
            'treatment_date' => 'required|date',
            'teeth_involved' => 'nullable|string|max:255',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'procedure_performed' => 'required|string',
            'materials_used' => 'nullable|string',
            'anesthesia_used' => 'nullable|string|max:255',
            'complications' => 'nullable|string',
            'post_treatment_instructions' => 'nullable|string',
            'next_appointment_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $treatmentRecord->update($validated);

        return redirect()->route('treatment-records.show', $treatmentRecord)
            ->with('success', 'Treatment record updated successfully.');
    }

    /**
     * Remove the specified treatment record from storage.
     */
    public function destroy(TreatmentRecord $treatmentRecord)
    {
        // Only administrators can delete treatment records
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can delete treatment records.');
        }

        $treatmentRecord->delete();

        return redirect()->route('treatment-records.index')
            ->with('success', 'Treatment record deleted successfully.');
    }

    /**
     * Show treatment records for a specific patient.
     */
    public function patientRecords(Patient $patient)
    {
        $treatmentRecords = $patient->treatmentRecords()
            ->with(['dentist', 'appointment'])
            ->orderBy('treatment_date', 'desc')
            ->paginate(20);

        return view('treatment-records.patient-records', compact('patient', 'treatmentRecords'));
    }
}