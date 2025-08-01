<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Procedure>
 */
class ProcedureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word . ' ' . $this->faker->randomElement(['Cleaning', 'Extraction', 'Filling', 'Root Canal', 'Crown']),
            'description' => $this->faker->sentence,
            'cost' => $this->faker->randomFloat(2, 50, 1000),
        ];
    }
}
