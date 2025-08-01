<?php

namespace Database\Seeders;

use App\Models\TreatmentPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TreatmentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TreatmentPlan::factory()->count(30)->create();
    }
}
