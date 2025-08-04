<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use App\Models\TreatmentPlan;
use App\Models\Procedure;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TreatmentRecord>
 */
class TreatmentRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $patient = Patient::inRandomOrder()->first();
        $dentist = User::role('dentist')->inRandomOrder()->first();
        $treatmentPlan = TreatmentPlan::inRandomOrder()->first();

        return [
            'patient_id' => $patient ? $patient->id : Patient::factory(),
            'dentist_id' => $dentist ? $dentist->id : User::factory(),
            'treatment_plan_id' => $treatmentPlan ? $treatmentPlan->id : TreatmentPlan::factory(),
            'treatment_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'treatment_notes' => $this->faker->paragraph,
            'treatment_outcome' => $this->faker->randomElement(['successful', 'partially successful', 'unsuccessful']),
            'treatment_cost' => $this->faker->randomFloat(2, 50, 2000),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (\App\Models\TreatmentRecord $record) {
            $procedures = Procedure::inRandomOrder()->take($this->faker->numberBetween(1, 3))->pluck('id');
            $record->procedures()->attach($procedures);
        });
    }
}
