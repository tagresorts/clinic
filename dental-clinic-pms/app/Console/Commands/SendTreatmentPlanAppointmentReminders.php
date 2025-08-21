<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\Setting;
use App\Notifications\TreatmentPlanAppointmentReminder;
use Illuminate\Console\Command;

class SendTreatmentPlanAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-treatment-plan-appointment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appointments = Appointment::where('appointment_datetime', '>', now())
            ->where('appointment_datetime', '<', now()->addDay())
            ->whereNotNull('treatment_plan_id')
            ->get();

        foreach ($appointments as $appointment) {
            $appointment->patient->notify(new TreatmentPlanAppointmentReminder($appointment));
        }

        $this->info('Sent ' . $appointments->count() . ' reminders.');
    }
}
