<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\TreatmentPlanController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProcedureController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard if authenticated
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard - role-based access
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', \Spatie\Permission\Middleware\PermissionMiddleware::class . ':view_dashboard'])
    ->name('dashboard');

Route::get('/dashboard-v3', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', \Spatie\Permission\Middleware\PermissionMiddleware::class . ':view_dashboard'])
    ->name('dashboard-v3');

Route::post('/dashboard/save-layout', [DashboardController::class, 'saveLayout'])
    ->middleware(['auth'])
    ->name('dashboard.saveLayout');

Route::post('/dashboard/reset-layout', [DashboardController::class, 'resetLayout'])
    ->middleware(['auth'])
    ->name('dashboard.resetLayout');

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
    // Local-only debug routes
    if (app()->environment('local')) {
        Route::get('patients/{patient}/debug-medical-history', [PatientController::class, 'debugMedicalHistory'])->name('patients.debug-medical-history');
        Route::get('treatment-plans/{treatmentPlan}/debug', [TreatmentPlanController::class, 'debugShow'])->name('treatment-plans.debug-show');
    }
});

// Procedure Management - Administrators only
Route::middleware(['auth', 'role:administrator'])->group(function () {
    Route::resource('procedures', ProcedureController::class);
});

// Appointment Management - Accessible by all roles
Route::middleware(['auth', 'role'])->group(function () {
    Route::get('appointments/feed', [AppointmentController::class, 'feed'])->name('appointments.feed');
    Route::get('appointments/summary', [AppointmentController::class, 'summary'])->name('appointments.summary');
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

// Billing and Invoicing - Receptionists and Administrators
Route::middleware(['auth', 'role:receptionist,administrator'])->group(function () {
    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
    Route::get('payments', [InvoiceController::class, 'payments'])->name('payments.index');
    Route::post('payments', [InvoiceController::class, 'storePayment'])->name('payments.store');
});

// Inventory Management - Administrators only
Route::middleware(['auth', 'role:administrator'])->group(function () {
    Route::get('inventory', function () {
        return view('inventory.index');
    })->name('inventory.index');
    
    Route::get('suppliers', function () {
        return view('suppliers.index');
    })->name('suppliers.index');
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

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\TablePreferenceController;

// User Management - Administrators only
Route::middleware(['auth', 'role:administrator'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::post('preferences/table', [TablePreferenceController::class, 'store'])
        ->name('preferences.table.store');
    
    Route::get('audit-logs', function () {
        return view('audit.index');
    })->name('audit.index');
});

require __DIR__.'/auth.php';
