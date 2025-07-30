<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{
    /**
     * Display a listing of patients.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Patient::with(['appointments' => function ($q) {
            $q->upcoming()->take(3);
        }])->active();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 20);
        $patients = $query->paginate($perPage);

        return response()->json([
            'patients' => $patients->items(),
            'pagination' => [
                'current_page' => $patients->currentPage(),
                'last_page' => $patients->lastPage(),
                'per_page' => $patients->perPage(),
                'total' => $patients->total(),
            ],
        ]);
    }

    /**
     * Store a newly created patient.
     */
    public function store(StorePatientRequest $request): JsonResponse
    {
        $patient = Patient::create($request->validated());

        return response()->json([
            'message' => 'Patient created successfully',
            'patient' => $patient->load(['appointments', 'treatmentPlans']),
        ], 201);
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient): JsonResponse
    {
        $patient->load([
            'appointments' => function ($query) {
                $query->with('dentist:id,first_name,last_name')
                      ->orderBy('date_time', 'desc')
                      ->take(10);
            },
            'treatmentPlans' => function ($query) {
                $query->with('dentist:id,first_name,last_name')
                      ->orderBy('created_at', 'desc');
            },
            'invoices' => function ($query) {
                $query->orderBy('date_issued', 'desc')
                      ->take(5);
            },
            'dentalCharts' => function ($query) {
                $query->orderBy('tooth_number');
            },
            'patientFiles' => function ($query) {
                $query->orderBy('uploaded_at', 'desc');
            },
        ]);

        return response()->json([
            'patient' => $patient,
        ]);
    }

    /**
     * Update the specified patient.
     */
    public function update(UpdatePatientRequest $request, Patient $patient): JsonResponse
    {
        $patient->update($request->validated());

        return response()->json([
            'message' => 'Patient updated successfully',
            'patient' => $patient->fresh(),
        ]);
    }

    /**
     * Remove the specified patient (soft delete).
     */
    public function destroy(Patient $patient): JsonResponse
    {
        $patient->update(['is_active' => false]);
        $patient->delete();

        return response()->json([
            'message' => 'Patient deleted successfully',
        ]);
    }

    /**
     * Get patient's dental chart.
     */
    public function getDentalChart(Patient $patient): JsonResponse
    {
        $dentalChart = $patient->dentalCharts()
                              ->orderBy('tooth_number')
                              ->get();

        return response()->json([
            'patient' => [
                'id' => $patient->id,
                'first_name' => $patient->first_name,
                'last_name' => $patient->last_name,
            ],
            'dental_chart' => $dentalChart,
        ]);
    }

    /**
     * Update patient's dental chart.
     */
    public function updateDentalChart(Request $request, Patient $patient): JsonResponse
    {
        $request->validate([
            'tooth_number' => 'required|integer|between:1,32',
            'condition' => 'required|string|max:200',
            'notes' => 'nullable|string|max:500',
        ]);

        $dentalChart = $patient->dentalCharts()->updateOrCreate(
            ['tooth_number' => $request->tooth_number],
            [
                'condition' => $request->condition,
                'notes' => $request->notes,
            ]
        );

        return response()->json([
            'message' => 'Dental chart updated successfully',
            'dental_chart_entry' => $dentalChart,
        ]);
    }

    /**
     * Get patient statistics.
     */
    public function getStats(Patient $patient): JsonResponse
    {
        $stats = [
            'total_appointments' => $patient->appointments()->count(),
            'completed_appointments' => $patient->appointments()->where('status', 'completed')->count(),
            'upcoming_appointments' => $patient->upcomingAppointments()->count(),
            'total_treatments' => $patient->treatmentRecords()->count(),
            'outstanding_balance' => $patient->outstanding_balance,
            'last_visit' => $patient->appointments()
                                   ->where('status', 'completed')
                                   ->latest('date_time')
                                   ->value('date_time'),
        ];

        return response()->json([
            'stats' => $stats,
        ]);
    }

    /**
     * Search patients by various criteria.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $patients = Patient::active()
                          ->search($request->query)
                          ->select('id', 'first_name', 'last_name', 'phone', 'email', 'date_of_birth')
                          ->limit($request->get('limit', 20))
                          ->get();

        return response()->json([
            'patients' => $patients,
        ]);
    }
}