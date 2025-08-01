<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\TreatmentPlanController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TreatmentRecordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\DentalChartController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard if authenticated
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
});

// Dashboard - role-based access
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role'])
    ->name('dashboard');

// Profile management for all authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Patient Management - Accessible by all roles (with different permissions)
Route::middleware(['auth', 'role'])->group(function () {
    Route::resource('patients', PatientController::class);
    Route::get('patients/{patient}/dental-chart', [PatientController::class, 'dentalChart'])->name('patients.dental-chart');
    Route::get('patients/{patient}/medical-history', [PatientController::class, 'medicalHistory'])->name('patients.medical-history');
});

// Appointment Management - Accessible by all roles
Route::middleware(['auth', 'role'])->group(function () {
    Route::resource('appointments', AppointmentController::class);
    Route::get('calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');
    Route::post('appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::post('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::post('appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
});

// Treatment Planning - Dentists and Administrators only
Route::middleware(['auth', 'role:dentist,administrator'])->group(function () {
    Route::resource('treatment-plans', TreatmentPlanController::class);
    Route::post('treatment-plans/{treatmentPlan}/approve', [TreatmentPlanController::class, 'approve'])->name('treatment-plans.approve');
    Route::post('treatment-plans/{treatmentPlan}/start', [TreatmentPlanController::class, 'start'])->name('treatment-plans.start');
    Route::post('treatment-plans/{treatmentPlan}/complete', [TreatmentPlanController::class, 'complete'])->name('treatment-plans.complete');
});

// Treatment Records - Dentists and Administrators only
Route::middleware(['auth', 'role:dentist,administrator'])->group(function () {
    Route::resource('treatment-records', TreatmentRecordController::class);
    Route::get('treatment-records/patient/{patient}', [TreatmentRecordController::class, 'patientRecords'])->name('treatment-records.patient');
});

// Dental Charts - Dentists and Administrators only
Route::middleware(['auth', 'role:dentist,administrator'])->group(function () {
    Route::resource('dental-charts', DentalChartController::class);
    Route::get('dental-charts/patient/{patient}', [DentalChartController::class, 'patientChart'])->name('dental-charts.patient');
    Route::post('dental-charts/patient/{patient}/bulk-update', [DentalChartController::class, 'bulkUpdate'])->name('dental-charts.bulk-update');
});

// Billing and Invoicing - Receptionists and Administrators
Route::middleware(['auth', 'role:receptionist,administrator'])->group(function () {
    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
});

// Payment Management - Receptionists and Administrators
Route::middleware(['auth', 'role:receptionist,administrator'])->group(function () {
    Route::resource('payments', PaymentController::class);
});

// Inventory Management - Administrators only
Route::middleware(['auth', 'role:administrator'])->group(function () {
    Route::resource('inventory', InventoryController::class);
    Route::post('inventory/{inventoryItem}/adjust-stock', [InventoryController::class, 'adjustStock'])->name('inventory.adjust-stock');
    
    Route::resource('suppliers', SupplierController::class);
});

// Reports - Administrators only
Route::middleware(['auth', 'role:administrator'])->group(function () {
    Route::get('reports', function () {
        return view('reports.index');
    })->name('reports.index');
    
    Route::get('reports/revenue', function () {
        return view('reports.revenue');
    })->name('reports.revenue');
    
    Route::get('reports/patients', function () {
        return view('reports.patients');
    })->name('reports.patients');
    
    Route::get('reports/treatments', function () {
        return view('reports.treatments');
    })->name('reports.treatments');
});

// User Management - Administrators only
Route::middleware(['auth', 'role:administrator'])->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
});

// Audit Logs - Administrators only
Route::middleware(['auth', 'role:administrator'])->group(function () {
    Route::resource('audit-logs', AuditController::class)->only(['index', 'show']);
    Route::get('audit-logs/export', [AuditController::class, 'export'])->name('audit-logs.export');
    Route::get('audit-logs/user/{userId}', [AuditController::class, 'userLogs'])->name('audit-logs.user');
    Route::get('audit-logs/model/{modelType}/{modelId}', [AuditController::class, 'modelLogs'])->name('audit-logs.model');
});

require __DIR__.'/auth.php';
