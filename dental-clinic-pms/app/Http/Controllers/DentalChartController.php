<?php

namespace App\Http\Controllers;

use App\Models\DentalChart;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DentalChartController extends Controller
{
    /**
     * Display a listing of dental charts for a patient.
     */
    public function index(Request $request)
    {
        // Only dentists and administrators can view dental charts
        if (!auth()->user()->isDentist() && !auth()->user()->isAdministrator()) {
            abort(403, 'Only dentists and administrators can view dental charts.');
        }

        $query = DentalChart::with(['patient']);

        // Search by patient
        if ($request->has('patient_id') && !empty($request->patient_id)) {
            $query->where('patient_id', $request->patient_id);
        }

        // Filter by tooth number
        if ($request->has('tooth_number') && !empty($request->tooth_number)) {
            $query->where('tooth_number', $request->tooth_number);
        }

        // Filter by condition
        if ($request->has('condition') && !empty($request->condition)) {
            $query->where('condition', $request->condition);
        }

        // For dentists, only show charts for their patients
        if (auth()->user()->isDentist()) {
            $query->whereHas('patient.appointments', function ($q) {
                $q->where('dentist_id', auth()->id());
            });
        }

        $dentalCharts = $query->orderBy('patient_id')
            ->orderBy('tooth_number')
            ->paginate(50);

        return view('dental-charts.index', compact('dentalCharts'));
    }

    /**
     * Show the form for creating a new dental chart entry.
     */
    public function create(Request $request)
    {
        // Only dentists and administrators can create dental chart entries
        if (!auth()->user()->isDentist() && !auth()->user()->isAdministrator()) {
            abort(403, 'Only dentists and administrators can create dental chart entries.');
        }

        $patient = null;
        if ($request->has('patient_id')) {
            $patient = Patient::findOrFail($request->patient_id);
        }

        $patients = Patient::orderBy('first_name')->get();

        return view('dental-charts.create', compact('patient', 'patients'));
    }

    /**
     * Store a newly created dental chart entry in storage.
     */
    public function store(Request $request)
    {
        // Only dentists and administrators can create dental chart entries
        if (!auth()->user()->isDentist() && !auth()->user()->isAdministrator()) {
            abort(403, 'Only dentists and administrators can create dental chart entries.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'tooth_number' => 'required|integer|between:1,32',
            'condition' => 'required|string|max:255',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
            'last_updated' => 'required|date|before_or_equal:today',
        ]);

        // Check if entry already exists for this tooth and patient
        $existingEntry = DentalChart::where('patient_id', $validated['patient_id'])
            ->where('tooth_number', $validated['tooth_number'])
            ->first();

        if ($existingEntry) {
            return back()->withErrors(['tooth_number' => 'An entry already exists for tooth ' . $validated['tooth_number'] . ' for this patient.']);
        }

        $dentalChart = DentalChart::create($validated);

        return redirect()->route('dental-charts.show', $dentalChart)
            ->with('success', 'Dental chart entry created successfully.');
    }

    /**
     * Display the specified dental chart entry.
     */
    public function show(DentalChart $dentalChart)
    {
        // Only dentists and administrators can view dental chart entries
        if (!auth()->user()->isDentist() && !auth()->user()->isAdministrator()) {
            abort(403, 'Only dentists and administrators can view dental chart entries.');
        }

        // For dentists, only show charts for their patients
        if (auth()->user()->isDentist()) {
            $hasAccess = $dentalChart->patient->appointments()
                ->where('dentist_id', auth()->id())
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You can only view dental charts for your patients.');
            }
        }

        $dentalChart->load('patient');

        return view('dental-charts.show', compact('dentalChart'));
    }

    /**
     * Show the form for editing the specified dental chart entry.
     */
    public function edit(DentalChart $dentalChart)
    {
        // Only dentists and administrators can edit dental chart entries
        if (!auth()->user()->isDentist() && !auth()->user()->isAdministrator()) {
            abort(403, 'Only dentists and administrators can edit dental chart entries.');
        }

        // For dentists, only allow editing charts for their patients
        if (auth()->user()->isDentist()) {
            $hasAccess = $dentalChart->patient->appointments()
                ->where('dentist_id', auth()->id())
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You can only edit dental charts for your patients.');
            }
        }

        $patients = Patient::orderBy('first_name')->get();

        return view('dental-charts.edit', compact('dentalChart', 'patients'));
    }

    /**
     * Update the specified dental chart entry in storage.
     */
    public function update(Request $request, DentalChart $dentalChart)
    {
        // Only dentists and administrators can edit dental chart entries
        if (!auth()->user()->isDentist() && !auth()->user()->isAdministrator()) {
            abort(403, 'Only dentists and administrators can edit dental chart entries.');
        }

        // For dentists, only allow editing charts for their patients
        if (auth()->user()->isDentist()) {
            $hasAccess = $dentalChart->patient->appointments()
                ->where('dentist_id', auth()->id())
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You can only edit dental charts for your patients.');
            }
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'tooth_number' => 'required|integer|between:1,32',
            'condition' => 'required|string|max:255',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
            'last_updated' => 'required|date',
        ]);

        // Check if entry already exists for this tooth and patient (excluding current entry)
        $existingEntry = DentalChart::where('patient_id', $validated['patient_id'])
            ->where('tooth_number', $validated['tooth_number'])
            ->where('id', '!=', $dentalChart->id)
            ->first();

        if ($existingEntry) {
            return back()->withErrors(['tooth_number' => 'An entry already exists for tooth ' . $validated['tooth_number'] . ' for this patient.']);
        }

        $dentalChart->update($validated);

        return redirect()->route('dental-charts.show', $dentalChart)
            ->with('success', 'Dental chart entry updated successfully.');
    }

    /**
     * Remove the specified dental chart entry from storage.
     */
    public function destroy(DentalChart $dentalChart)
    {
        // Only administrators can delete dental chart entries
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can delete dental chart entries.');
        }

        $dentalChart->delete();

        return redirect()->route('dental-charts.index')
            ->with('success', 'Dental chart entry deleted successfully.');
    }

    /**
     * Show dental chart for a specific patient.
     */
    public function patientChart(Patient $patient)
    {
        // Only dentists and administrators can view dental charts
        if (!auth()->user()->isDentist() && !auth()->user()->isAdministrator()) {
            abort(403, 'Only dentists and administrators can view dental charts.');
        }

        // For dentists, only show charts for their patients
        if (auth()->user()->isDentist()) {
            $hasAccess = $patient->appointments()
                ->where('dentist_id', auth()->id())
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You can only view dental charts for your patients.');
            }
        }

        $dentalCharts = $patient->dentalCharts()
            ->orderBy('tooth_number')
            ->get();

        return view('dental-charts.patient-chart', compact('patient', 'dentalCharts'));
    }

    /**
     * Bulk update dental chart for a patient.
     */
    public function bulkUpdate(Request $request, Patient $patient)
    {
        // Only dentists and administrators can update dental charts
        if (!auth()->user()->isDentist() && !auth()->user()->isAdministrator()) {
            abort(403, 'Only dentists and administrators can update dental charts.');
        }

        // For dentists, only allow updating charts for their patients
        if (auth()->user()->isDentist()) {
            $hasAccess = $patient->appointments()
                ->where('dentist_id', auth()->id())
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You can only update dental charts for your patients.');
            }
        }

        $validated = $request->validate([
            'teeth' => 'required|array',
            'teeth.*.tooth_number' => 'required|integer|between:1,32',
            'teeth.*.condition' => 'required|string|max:255',
            'teeth.*.treatment_plan' => 'nullable|string',
            'teeth.*.notes' => 'nullable|string',
        ]);

        foreach ($validated['teeth'] as $toothData) {
            DentalChart::updateOrCreate(
                [
                    'patient_id' => $patient->id,
                    'tooth_number' => $toothData['tooth_number']
                ],
                [
                    'condition' => $toothData['condition'],
                    'treatment_plan' => $toothData['treatment_plan'] ?? null,
                    'notes' => $toothData['notes'] ?? null,
                    'last_updated' => now(),
                ]
            );
        }

        return redirect()->route('dental-charts.patient-chart', $patient)
            ->with('success', 'Dental chart updated successfully.');
    }
}