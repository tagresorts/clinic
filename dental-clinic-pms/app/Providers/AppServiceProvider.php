<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\TreatmentPlan;
use App\Models\Invoice;
use App\Observers\AppointmentObserver;
use App\Observers\PatientObserver;
use App\Observers\UserObserver;
use App\Observers\TreatmentPlanObserver;
use App\Observers\InvoiceObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Appointment::observe(AppointmentObserver::class);
        Patient::observe(PatientObserver::class);
        User::observe(UserObserver::class);
        TreatmentPlan::observe(TreatmentPlanObserver::class);
        Invoice::observe(InvoiceObserver::class);
    }
}
