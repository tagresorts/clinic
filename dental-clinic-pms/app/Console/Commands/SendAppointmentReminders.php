<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\TentativeAppointmentReminder;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders to doctors for tentative appointments.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending appointment reminders...');

        $reminders = Appointment::with('dentist', 'patient')
            ->where('status', Appointment::STATUS_TENTATIVE)
            ->whereDate('appointment_datetime', now()->addWeek()->toDateString())
            ->get()
            ->groupBy('dentist_id');

        foreach ($reminders as $dentistId => $appointments) {
            $dentist = User::find($dentistId);
            if ($dentist) {
                Mail::to($dentist)->send(new TentativeAppointmentReminder($appointments));
                $this->info("Reminder sent to {$dentist->name}");
            }
        }

        $this->info('Done.');
    }
}
