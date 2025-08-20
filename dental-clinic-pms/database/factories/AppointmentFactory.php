<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $patientIds = Patient::pluck('id')->toArray();
        $dentistIds = User::role('dentist')->pluck('id')->toArray();

        $appointmentDatetime = Carbon::parse($this->faker->dateTimeBetween('-3 months', '+3 months'))
            ->setTime($this->faker->numberBetween(8, 17), $this->faker->randomElement([0, 15, 30, 45]), 0);
        $status = $this->getRandomStatus($appointmentDatetime);

        return [
            'patient_id' => $this->faker->randomElement($patientIds),
            'dentist_id' => $this->faker->randomElement($dentistIds),
            'appointment_datetime' => $appointmentDatetime,
            'duration_minutes' => $this->faker->randomElement([15, 30, 45, 60]),
            'appointment_type' => $this->faker->randomElement(['Consultation', 'Cleaning', 'Extraction', 'Filling', 'Check-up']),
            'status' => $status,
            'reason_for_visit' => $this->faker->sentence,
            'appointment_notes' => $this->faker->optional()->paragraph,
            'cancellation_reason' => $status === 'cancelled' ? $this->faker->sentence : null,
        ];
    }

    /**
     * Get a realistic status based on the appointment date.
     */
    private function getRandomStatus(Carbon $datetime): string
    {
        if ($datetime->isFuture()) {
            return $this->faker->randomElement([
                Appointment::STATUS_SCHEDULED,
                Appointment::STATUS_CONFIRMED,
                Appointment::STATUS_CANCELLED
            ]);
        } else {
            return $this->faker->randomElement([
                Appointment::STATUS_COMPLETED,
                Appointment::STATUS_CANCELLED,
                Appointment::STATUS_NO_SHOW
            ]);
        }
    }
}
