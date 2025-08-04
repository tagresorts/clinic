<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use App\Models\TreatmentPlan;
use App\Models\Procedure;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TreatmentPlan>
 */
class TreatmentPlanFactory extends Factory
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

        return [
            'patient_id' => $patient ? $patient->id : Patient::factory(),
            'dentist_id' => $dentist ? $dentist->id : User::factory(),
            'plan_title' => $this->faker->sentence(3),
            'diagnosis' => $this->faker->paragraph(2),
            'estimated_cost' => $this->faker->randomFloat(2, 100, 5000),
            'estimated_duration_sessions' => $this->faker->numberBetween(1, 10),
            'priority' => $this->faker->randomElement([TreatmentPlan::PRIORITY_LOW, TreatmentPlan::PRIORITY_MEDIUM, TreatmentPlan::PRIORITY_HIGH, TreatmentPlan::PRIORITY_URGENT]),
            'status' => $this->faker->randomElement([TreatmentPlan::STATUS_PROPOSED, TreatmentPlan::STATUS_PATIENT_APPROVED, TreatmentPlan::STATUS_IN_PROGRESS, TreatmentPlan::STATUS_COMPLETED, TreatmentPlan::STATUS_CANCELLED]),
            'treatment_notes' => $this->faker->paragraph,
            'patient_concerns' => $this->faker->paragraph,
            'dentist_notes' => $this->faker->paragraph,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (TreatmentPlan $plan) {
            $procedures = Procedure::inRandomOrder()->take($this->faker->numberBetween(1, 3))->pluck('id');
            $plan->procedures()->attach($procedures);
        });
    }
}
