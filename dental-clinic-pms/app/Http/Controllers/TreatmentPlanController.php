<?php

namespace App\Http\Controllers;

use App\Models\TreatmentPlan;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TreatmentPlanController extends Controller
{
    /**
     * Display a listing of treatment plans.
     */
    public function index(Request $request)
    {
        $query = TreatmentPlan::with(['patient', 'dentist']);

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

        // Filter by priority
        if ($request->has('priority') && !empty($request->priority)) {
            $query->where('priority', $request->priority);
        }

        // For dentists, only show their treatment plans
        if (auth()->user()->isDentist()) {
            $query->where('dentist_id', auth()->id());
        }

        $treatmentPlans = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('treatment-plans.index', compact('treatmentPlans'));
    }

    /**
     * Show the form for creating a new treatment plan.
     */
    public function create(Request $request)
    {
        // Only dentists and administrators can create treatment plans
        if (!auth()->user()->isDentist() && !auth()->user()->isAdministrator()) {
            abort(403, 'Only dentists and administrators can create treatment plans.');
        }

        $patient = null;
        if ($request->has('patient_id')) {
            $patient = Patient::findOrFail($request->patient_id);
        }

        $patients = Patient::orderBy('first_name')->get();
        $dentists = User::where('role', 'dentist')->where('is_active', true)->orderBy('name')->get();

        return view('treatment-plans.create', compact('patient', 'patients', 'dentists'));
    }

    /**
     * Store a newly created treatment plan in storage.
     */
    public function store(Request $request)
    {
        // Only dentists and administrators can create treatment plans
        if (!auth()->user()->isDentist() && !auth()->user()->isAdministrator()) {
            abort(403, 'Only dentists and administrators can create treatment plans.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'procedures' => 'required|array',
            'procedures.*.procedure_name' => 'required|string|max:255',
            'procedures.*.description' => 'nullable|string',
            'procedures.*.estimated_cost' => 'nullable|numeric|min:0',
            'procedures.*.priority' => 'required|in:high,medium,low',
            'procedures.*.estimated_duration' => 'nullable|integer|min:1',
            'total_estimated_cost' => 'required|numeric|min:0',
            'priority' => 'required|in:high,medium,low',
            'estimated_completion_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,pending_approval,approved,in_progress,completed,cancelled',
        ]);

        // Set dentist_id to current user if they are a dentist
        if (auth()->user()->isDentist()) {
            $validated['dentist_id'] = auth()->id();
        }

        $treatmentPlan = TreatmentPlan::create($validated);

        return redirect()->route('treatment-plans.show', $treatmentPlan)
            ->with('success', 'Treatment plan created successfully.');
    }

    /**
     * Display the specified treatment plan.
     */
    public function show(TreatmentPlan $treatmentPlan)
    {
        $treatmentPlan->load(['patient', 'dentist', 'appointments']);

        return view('treatment-plans.show', compact('treatmentPlan'));
    }

    /**
     * Show the form for editing the specified treatment plan.
     */
    public function edit(TreatmentPlan $treatmentPlan)
    {
        // Only the treating dentist or administrators can edit treatment plans
        if (!auth()->user()->isAdministrator() && 
            (!auth()->user()->isDentist() || $treatmentPlan->dentist_id !== auth()->id())) {
            abort(403, 'You can only edit your own treatment plans.');
        }

        // Only allow editing if not completed
        if ($treatmentPlan->status === 'completed') {
            return redirect()->route('treatment-plans.show', $treatmentPlan)
                ->with('error', 'Cannot edit completed treatment plans.');
        }

        $patients = Patient::orderBy('first_name')->get();
        $dentists = User::where('role', 'dentist')->where('is_active', true)->orderBy('name')->get();

        return view('treatment-plans.edit', compact('treatmentPlan', 'patients', 'dentists'));
    }

    /**
     * Update the specified treatment plan in storage.
     */
    public function update(Request $request, TreatmentPlan $treatmentPlan)
    {
        // Only the treating dentist or administrators can edit treatment plans
        if (!auth()->user()->isAdministrator() && 
            (!auth()->user()->isDentist() || $treatmentPlan->dentist_id !== auth()->id())) {
            abort(403, 'You can only edit your own treatment plans.');
        }

        // Only allow updating if not completed
        if ($treatmentPlan->status === 'completed') {
            return redirect()->route('treatment-plans.show', $treatmentPlan)
                ->with('error', 'Cannot edit completed treatment plans.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'procedures' => 'required|array',
            'procedures.*.procedure_name' => 'required|string|max:255',
            'procedures.*.description' => 'nullable|string',
            'procedures.*.estimated_cost' => 'nullable|numeric|min:0',
            'procedures.*.priority' => 'required|in:high,medium,low',
            'procedures.*.estimated_duration' => 'nullable|integer|min:1',
            'total_estimated_cost' => 'required|numeric|min:0',
            'priority' => 'required|in:high,medium,low',
            'estimated_completion_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,pending_approval,approved,in_progress,completed,cancelled',
        ]);

        $treatmentPlan->update($validated);

        return redirect()->route('treatment-plans.show', $treatmentPlan)
            ->with('success', 'Treatment plan updated successfully.');
    }

    /**
     * Remove the specified treatment plan from storage.
     */
    public function destroy(TreatmentPlan $treatmentPlan)
    {
        // Only administrators can delete treatment plans
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can delete treatment plans.');
        }

        // Only allow deletion if not in progress or completed
        if (in_array($treatmentPlan->status, ['in_progress', 'completed'])) {
            return redirect()->route('treatment-plans.show', $treatmentPlan)
                ->with('error', 'Cannot delete treatment plans that are in progress or completed.');
        }

        $treatmentPlan->delete();

        return redirect()->route('treatment-plans.index')
            ->with('success', 'Treatment plan deleted successfully.');
    }

    /**
     * Approve a treatment plan.
     */
    public function approve(TreatmentPlan $treatmentPlan)
    {
        // Only administrators can approve treatment plans
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can approve treatment plans.');
        }

        $treatmentPlan->update(['status' => 'approved']);

        return redirect()->route('treatment-plans.show', $treatmentPlan)
            ->with('success', 'Treatment plan approved successfully.');
    }

    /**
     * Start a treatment plan.
     */
    public function start(TreatmentPlan $treatmentPlan)
    {
        // Only the treating dentist or administrators can start treatment plans
        if (!auth()->user()->isAdministrator() && 
            (!auth()->user()->isDentist() || $treatmentPlan->dentist_id !== auth()->id())) {
            abort(403, 'You can only start your own treatment plans.');
        }

        if ($treatmentPlan->status !== 'approved') {
            return redirect()->route('treatment-plans.show', $treatmentPlan)
                ->with('error', 'Only approved treatment plans can be started.');
        }

        $treatmentPlan->update(['status' => 'in_progress']);

        return redirect()->route('treatment-plans.show', $treatmentPlan)
            ->with('success', 'Treatment plan started successfully.');
    }

    /**
     * Complete a treatment plan.
     */
    public function complete(TreatmentPlan $treatmentPlan)
    {
        // Only the treating dentist or administrators can complete treatment plans
        if (!auth()->user()->isAdministrator() && 
            (!auth()->user()->isDentist() || $treatmentPlan->dentist_id !== auth()->id())) {
            abort(403, 'You can only complete your own treatment plans.');
        }

        if ($treatmentPlan->status !== 'in_progress') {
            return redirect()->route('treatment-plans.show', $treatmentPlan)
                ->with('error', 'Only treatment plans in progress can be completed.');
        }

        $treatmentPlan->update(['status' => 'completed']);

        return redirect()->route('treatment-plans.show', $treatmentPlan)
            ->with('success', 'Treatment plan completed successfully.');
    }
}
