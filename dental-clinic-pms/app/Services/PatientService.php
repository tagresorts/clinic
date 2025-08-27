<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientService
{
    public function getAllPatients(Request $request)
    {
        $query = Patient::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Filter by status: active (default), deactivated (soft-deleted), or all
        if ($request->filled('status')) {
            if ($request->status === 'deactivated') {
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

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    public function createPatient(array $data): Patient
    {
        try {
            return Patient::create($data);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating patient: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updatePatient(Patient $patient, array $data): Patient
    {
        try {
            $patient->update($data);
            return $patient;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating patient: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deletePatient(Patient $patient): void
    {
        try {
            $patient->delete();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error deleting patient: ' . $e->getMessage());
            throw $e;
        }
    }

    public function restorePatient(int $id): Patient
    {
        try {
            $patient = Patient::withTrashed()->findOrFail($id);
            $patient->restore();
            return $patient;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error restoring patient: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getDentalChart(Patient $patient)
    {
        return $patient->dentalCharts()->orderBy('tooth_number')->get();
    }

    public function getMedicalHistory(Patient $patient): array
    {
        $treatmentRecords = $patient->treatmentRecords()
            ->with(['dentist', 'appointment', 'treatmentPlan', 'procedures'])
            ->orderBy('treatment_date', 'desc')
            ->get();

        $treatmentPlans = $patient->treatmentPlans()
            ->with(['dentist'])
            ->orderBy('created_at', 'desc')
            ->get();

        return ['treatmentRecords' => $treatmentRecords, 'treatmentPlans' => $treatmentPlans];
    }

    use App\Models\Appointment;
use App\Models\Procedure;
use App\Models\TreatmentRecord;
use Illuminate\Support\Facades\DB;

    public function createWalkIn(Patient $patient, array $data): void
    {
        try {
            DB::transaction(function () use ($data, $patient) {
                $appointment = Appointment::create([
                    'patient_id' => $patient->id,
                    'dentist_id' => $data['dentist_id'],
                    'appointment_datetime' => $data['appointment_datetime'],
                    'duration_minutes' => $data['duration_minutes'],
                    'appointment_type' => $data['appointment_type'],
                    'status' => Appointment::STATUS_IN_PROGRESS,
                    'reason_for_visit' => $data['reason_for_visit'],
                    'appointment_notes' => $data['appointment_notes'],
                ]);

                $procedures = Procedure::whereIn('id', $data['procedure_ids'])->get();
                $totalCost = $procedures->sum('cost');

                $treatmentRecord = TreatmentRecord::create([
                    'patient_id' => $patient->id,
                    'dentist_id' => $data['dentist_id'],
                    'appointment_id' => $appointment->id,
                    'treatment_date' => now(),
                    'description' => $data['description'],
                    'teeth' => $data['teeth'],
                    'treatment_cost' => $totalCost,
                    'notes' => $data['treatment_notes'],
                ]);

                $treatmentRecord->procedures()->attach($data['procedure_ids']);
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating walk-in appointment and treatment: ' . $e->getMessage());
            throw $e;
        }
    }
}
