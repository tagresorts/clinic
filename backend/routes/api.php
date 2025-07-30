<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\TreatmentController;
use App\Http\Controllers\Api\BillingController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::post('/register', [AuthController::class, 'register']); // Admin only
    });

    // Patient routes
    Route::prefix('patients')->group(function () {
        Route::get('/', [PatientController::class, 'index']);
        Route::post('/', [PatientController::class, 'store']);
        Route::get('/search', [PatientController::class, 'search']);
        Route::get('/{patient}', [PatientController::class, 'show']);
        Route::put('/{patient}', [PatientController::class, 'update']);
        Route::delete('/{patient}', [PatientController::class, 'destroy']);
        Route::get('/{patient}/stats', [PatientController::class, 'getStats']);
        Route::get('/{patient}/dental-chart', [PatientController::class, 'getDentalChart']);
        Route::post('/{patient}/dental-chart', [PatientController::class, 'updateDentalChart']);
    });

    // Appointment routes
    Route::prefix('appointments')->group(function () {
        Route::get('/', [AppointmentController::class, 'index']);
        Route::post('/', [AppointmentController::class, 'store']);
        Route::get('/calendar', [AppointmentController::class, 'calendar']);
        Route::get('/available-slots', [AppointmentController::class, 'availableSlots']);
        Route::get('/upcoming', [AppointmentController::class, 'upcoming']);
        Route::get('/{appointment}', [AppointmentController::class, 'show']);
        Route::put('/{appointment}', [AppointmentController::class, 'update']);
        Route::delete('/{appointment}', [AppointmentController::class, 'destroy']);
    });

    // Treatment routes
    Route::prefix('treatments')->group(function () {
        Route::get('/plans', [TreatmentController::class, 'plans']);
        Route::post('/plans', [TreatmentController::class, 'storePlan']);
        Route::get('/plans/{treatmentPlan}', [TreatmentController::class, 'showPlan']);
        Route::put('/plans/{treatmentPlan}', [TreatmentController::class, 'updatePlan']);
        Route::delete('/plans/{treatmentPlan}', [TreatmentController::class, 'destroyPlan']);
        
        Route::get('/records', [TreatmentController::class, 'records']);
        Route::post('/records', [TreatmentController::class, 'storeRecord']);
        Route::get('/records/{treatmentRecord}', [TreatmentController::class, 'showRecord']);
        Route::put('/records/{treatmentRecord}', [TreatmentController::class, 'updateRecord']);
        Route::delete('/records/{treatmentRecord}', [TreatmentController::class, 'destroyRecord']);
    });

    // Billing routes
    Route::prefix('billing')->group(function () {
        Route::get('/invoices', [BillingController::class, 'invoices']);
        Route::post('/invoices', [BillingController::class, 'storeInvoice']);
        Route::get('/invoices/{invoice}', [BillingController::class, 'showInvoice']);
        Route::put('/invoices/{invoice}', [BillingController::class, 'updateInvoice']);
        Route::delete('/invoices/{invoice}', [BillingController::class, 'destroyInvoice']);
        
        Route::post('/payments', [BillingController::class, 'storePayment']);
        Route::get('/payments', [BillingController::class, 'payments']);
        Route::get('/outstanding', [BillingController::class, 'outstanding']);
    });

    // Inventory routes (Admin only)
    Route::prefix('inventory')->middleware('role:administrator')->group(function () {
        Route::get('/items', [InventoryController::class, 'items']);
        Route::post('/items', [InventoryController::class, 'storeItem']);
        Route::get('/items/{inventoryItem}', [InventoryController::class, 'showItem']);
        Route::put('/items/{inventoryItem}', [InventoryController::class, 'updateItem']);
        Route::delete('/items/{inventoryItem}', [InventoryController::class, 'destroyItem']);
        
        Route::get('/suppliers', [InventoryController::class, 'suppliers']);
        Route::post('/suppliers', [InventoryController::class, 'storeSupplier']);
        Route::get('/low-stock', [InventoryController::class, 'lowStock']);
        Route::post('/stock-movement', [InventoryController::class, 'recordStockMovement']);
    });

    // Report routes (Admin only)
    Route::prefix('reports')->middleware('role:administrator')->group(function () {
        Route::get('/dashboard', [ReportController::class, 'dashboard']);
        Route::get('/financial', [ReportController::class, 'financial']);
        Route::get('/patients', [ReportController::class, 'patients']);
        Route::get('/appointments', [ReportController::class, 'appointments']);
        Route::get('/treatments', [ReportController::class, 'treatments']);
    });

    // User management routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']); // Admin only
        Route::get('/dentists', [UserController::class, 'dentists']);
        Route::put('/{user}', [UserController::class, 'update']); // Admin only
        Route::delete('/{user}', [UserController::class, 'destroy']); // Admin only
        Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus']); // Admin only
    });
});

// Fallback route for undefined API endpoints
Route::fallback(function () {
    return response()->json([
        'error' => 'Route not found',
        'message' => 'The requested API endpoint was not found.',
    ], 404);
});