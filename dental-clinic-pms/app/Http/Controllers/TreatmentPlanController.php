<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class TreatmentPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $treatmentPlans = \App\Models\TreatmentPlan::with(['patient', 'dentist'])->latest()->paginate(20);

        return view('treatment-plans.index', compact('treatmentPlans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = \App\Models\Patient::all();
        $dentists = \App\Models\User::role('dentist')->get();
        $procedures = \App\Models\Procedure::all();

        return view('treatment-plans.create', compact('patients', 'dentists', 'procedures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'plan_title' => 'required|string|max:255',
            'diagnosis' => 'required|string',
            'procedure_ids' => 'required|array',
            'procedure_ids.*' => 'exists:procedures,id',
            'estimated_cost' => 'required|numeric',
            'estimated_duration_sessions' => 'required|integer',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:proposed,patient_approved,in_progress,completed,cancelled',
        ]);

        $plan = \App\Models\TreatmentPlan::create($validated);
        $plan->procedures()->sync($validated['procedure_ids']);

        // If the treatment plan is created with a proposed or approved status, create tentative appointments
        if (in_array($validated['status'], [
            \App\Models\TreatmentPlan::STATUS_PROPOSED,
            \App\Models\TreatmentPlan::STATUS_PATIENT_APPROVED
        ])) {
            $sessions = (int) $validated['estimated_duration_sessions'];
            $startDate = now()->addWeek()->startOfWeek();

            for ($i = 0; $i < $sessions; $i++) {
                Appointment::create([
                    'patient_id' => $plan->patient_id,
                    'dentist_id' => $plan->dentist_id,
                    'appointment_datetime' => $startDate->copy()->addWeeks($i),
                    'duration_minutes' => 60, // Default to 60 minutes
                    'appointment_type' => 'Treatment Plan Session',
                    'status' => \App\Models\Appointment::STATUS_TENTATIVE,
                    'reason_for_visit' => $plan->plan_title,
                ]);
            }
        }

        // If the treatment plan is created with a completed status, create a treatment record
        if ($validated['status'] === \App\Models\TreatmentPlan::STATUS_COMPLETED) {
            $record = \App\Models\TreatmentRecord::create([
                'patient_id' => $plan->patient_id,
                'dentist_id' => $plan->dentist_id,
                'treatment_plan_id' => $plan->id,
                'treatment_date' => now(),
                'treatment_notes' => $plan->treatment_notes ?? 'Treatment plan created as completed.',
            ]);
            $record->procedures()->sync($validated['procedure_ids']);
        }

        return redirect()->route('treatment-plans.index')
            ->with('success', 'Treatment plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $plan = \App\Models\TreatmentPlan::with(['patient', 'dentist', 'procedures'])->findOrFail($id);
        $tentativeAppointments = Appointment::where('patient_id', $plan->patient_id)
            ->where('status', Appointment::STATUS_TENTATIVE)
            ->where('reason_for_visit', $plan->plan_title)
            ->orderBy('appointment_datetime')
            ->get();

        return view('treatment-plans.show', compact('plan', 'tentativeAppointments'));
    }

    public function debugShow(string $id)
    {
        $plan = \App\Models\TreatmentPlan::with(['patient', 'dentist'])->findOrFail($id);
        dd($plan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $plan = \App\Models\TreatmentPlan::with('procedures')->findOrFail($id);
        $patients = \App\Models\Patient::all();
        $dentists = \App\Models\User::role('dentist')->get();
        $procedures = \App\Models\Procedure::all();
        $tentativeAppointments = Appointment::where('patient_id', $plan->patient_id)
            ->where('status', Appointment::STATUS_TENTATIVE)
            ->where('reason_for_visit', $plan->plan_title)
            ->orderBy('appointment_datetime')
            ->get();

        return view('treatment-plans.edit', compact('plan', 'patients', 'dentists', 'procedures', 'tentativeAppointments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $plan = \App\Models\TreatmentPlan::findOrFail($id);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'plan_title' => 'required|string|max:255',
            'diagnosis' => 'required|string',
            'procedure_ids' => 'required|array',
            'procedure_ids.*' => 'exists:procedures,id',
            'estimated_cost' => 'required|numeric',
            'estimated_duration_sessions' => 'required|integer',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:proposed,patient_approved,in_progress,completed,cancelled',
            'appointment_dates' => 'nullable|array',
            'appointment_dates.*' => 'nullable|date',
        ]);

        $plan->update($validated);
        $plan->procedures()->sync($validated['procedure_ids']);

        // If the treatment plan is marked as completed, create a treatment record
        if ($validated['status'] === \App\Models\TreatmentPlan::STATUS_COMPLETED) {
            $record = \App\Models\TreatmentRecord::create([
                'patient_id' => $plan->patient_id,
                'dentist_id' => $plan->dentist_id,
                'treatment_plan_id' => $plan->id,
                'treatment_date' => now(),
                'treatment_notes' => $plan->treatment_notes ?? 'Treatment plan completed.',
            ]);
            $record->procedures()->sync($validated['procedure_ids']);
        }

        // Update tentative appointments
        if (isset($validated['appointment_dates'])) {
            $existingAppointments = Appointment::where('patient_id', $plan->patient_id)
                ->where('status', Appointment::STATUS_TENTATIVE)
                ->where('reason_for_visit', $plan->plan_title)
                ->orderBy('appointment_datetime')
                ->get();

            $submittedDates = $validated['appointment_dates'];

            // Update existing appointments and create new ones
            foreach ($submittedDates as $index => $date) {
                if (isset($existingAppointments[$index])) {
                    $existingAppointments[$index]->update(['appointment_datetime' => $date]);
                } else {
                    Appointment::create([
                        'patient_id' => $plan->patient_id,
                        'dentist_id' => $plan->dentist_id,
                        'treatment_plan_id' => $plan->id,
                        'appointment_datetime' => $date,
                        'duration_minutes' => 60, // Default to 60 minutes
                        'appointment_type' => 'Treatment Plan Session',
                        'status' => \App\Models\Appointment::STATUS_TENTATIVE,
                        'reason_for_visit' => $plan->plan_title,
                    ]);
                }
            }

            // Delete extra appointments
            if (count($existingAppointments) > count($submittedDates)) {
                for ($i = count($submittedDates); $i < count($existingAppointments); $i++) {
                    $existingAppointments[$i]->delete();
                }
            }
        }


        return redirect()->route('treatment-plans.index')
            ->with('success', 'Treatment plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $plan = \App\Models\TreatmentPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('treatment-plans.index')
            ->with('success', 'Treatment plan deleted successfully.');
    }
}
