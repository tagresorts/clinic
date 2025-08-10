<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PatientController extends Controller
{
    /**
     * Display a listing of patients.
     */
    public function index(Request $request)
    {
        $query = Patient::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Filter by status: active (default), deactivated (soft-deleted), or all
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                // default behavior (exclude soft-deleted)
                $query->active();
            } elseif ($request->status === 'deactivated') {
                $query = Patient::onlyTrashed();
            } elseif ($request->status === 'all') {
                $query = Patient::withTrashed();
            }
        }

        // For dentists, only show their patients (from appointments)
        if (auth()->user()->hasRole('dentist')) {
            $query->whereHas('appointments', function ($q) {
                $q->where('dentist_id', auth()->id());
            });
        }

        $patients = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        // Only receptionists and administrators can create patients
        if (!auth()->user()->hasRole(['administrator', 'receptionist'])) {
            abort(403, 'Only receptionists and administrators can register new patients.');
        }

        return view('patients.create');
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request)
    {
        // Intentionally avoid logging request bodies or PHI
        // Only receptionists and administrators can create patients
        if (!auth()->user()->hasRole(['administrator', 'receptionist'])) {
            \Illuminate\Support\Facades\Log::warning('Unauthorized attempt to create patient by user: ' . auth()->id());
            abort(403, 'Only receptionists and administrators can register new patients.');
        }

        // Avoid logging full request payloads containing PHI

        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'date_of_birth' => 'required|date|before:today',
                'gender' => 'required|in:male,female,other',
                'address' => 'required|string',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'emergency_contact_name' => 'required|string|max:255',
                'emergency_contact_phone' => 'required|string|max:20',
                'emergency_contact_relationship' => 'required|string|max:100',
                'allergies' => 'nullable|string',
                'medical_conditions' => 'nullable|string',
                'current_medications' => 'nullable|string',
                'medical_notes' => 'nullable|string',
                'dental_history' => 'nullable|string',
                'previous_treatments' => 'nullable|string',
                'dental_notes' => 'nullable|string',
                'insurance_provider' => 'nullable|string|max:255',
                'insurance_policy_number' => 'nullable|string|max:100',
                'insurance_group_number' => 'nullable|string|max:100',
                'insurance_expiry_date' => 'nullable|date|after:today',
            ]);
            // Avoid logging validated PHI
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validation failed: ', $e->errors());
            throw $e;
        }

        try {
            $patient = Patient::create($validated);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating patient: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to register patient. Please try again.');
        }

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient registered successfully.');
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient)
    {
        // Load relationships
        $patient->load(['appointments.dentist', 'treatmentPlans', 'invoices', 'dentalCharts']);

        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient)
    {
        // Only receptionists and administrators can edit patient demographics
        if (!auth()->user()->hasRole(['administrator', 'receptionist'])) {
            abort(403, 'Only receptionists and administrators can edit patient information.');
        }

        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        // Only receptionists and administrators can edit patient demographics
        if (!auth()->user()->hasRole(['administrator', 'receptionist'])) {
            abort(403, 'Only receptionists and administrators can edit patient information.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:100',
            'allergies' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'dental_history' => 'nullable|string',
            'previous_treatments' => 'nullable|string',
            'dental_notes' => 'nullable|string',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:100',
            'insurance_group_number' => 'nullable|string|max:100',
            'insurance_expiry_date' => 'nullable|date|after:today',
            'is_active' => 'boolean',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient information updated successfully.');
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy(Patient $patient)
    {
        // Authorization via permission
        if (!auth()->user()->can('patient-delete')) {
            abort(403, 'You are not authorized to delete patients.');
        }

        // Soft delete
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Patient has been deactivated successfully.');
    }

    /**
     * Restore a previously deactivated (soft-deleted) patient.
     */
    public function restore(int $id)
    {
        if (!auth()->user()->can('patient-edit')) {
            abort(403, 'You are not authorized to restore patients.');
        }

        $patient = Patient::withTrashed()->findOrFail($id);
        $patient->restore();

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient has been reactivated successfully.');
    }

    /**
     * Show dental chart for the patient.
     */
    public function dentalChart(Patient $patient)
    {
        // Only dentists and administrators can view dental charts
        if (!auth()->user()->hasRole(['dentist', 'administrator'])) {
            abort(403, 'Only dentists and administrators can view dental charts.');
        }

        $dentalChart = $patient->dentalCharts()->orderBy('tooth_number')->get();

        return view('patients.dental-chart', compact('patient', 'dentalChart'));
    }

    /**
     * Show medical history for the patient.
     */
    public function medicalHistory(Patient $patient)
    {
        $treatmentRecords = $patient->treatmentRecords()
            ->with(['dentist', 'appointment', 'treatmentPlan', 'procedures'])
            ->orderBy('treatment_date', 'desc')
            ->get();

        $treatmentPlans = $patient->treatmentPlans()
            ->with(['dentist'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('patients.medical-history', compact('patient', 'treatmentRecords', 'treatmentPlans'));
    }

    public function debugMedicalHistory(Patient $patient)
    {
        $treatmentRecords = $patient->treatmentRecords()
            ->with(['dentist', 'appointment', 'treatmentPlan', 'procedures'])
            ->orderBy('treatment_date', 'desc')
            ->get();

        dd($treatmentRecords);
    }
}
