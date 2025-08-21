<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TreatmentPlanAppointmentReminderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailTemplate::create([
            'name' => 'Treatment Plan Appointment Reminder',
            'subject' => 'Upcoming Appointment for your Treatment Plan',
            'type' => 'treatment_plan_appointment_reminder',
            'body' => '<p>Dear {patient_name},</p><p>This is a reminder that you have an upcoming appointment for your treatment plan on {appointment_date} at {appointment_time}.</p><p>Thank you,</p><p>The Clinic</p>',
            'wildcards' => json_encode(['{patient_name}', '{appointment_date}', '{appointment_time}']),
        ]);
    }
}
