<?php

namespace Database\Seeders;

use App\Models\TreatmentRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TreatmentRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TreatmentRecord::factory()->count(50)->create();
    }
}
