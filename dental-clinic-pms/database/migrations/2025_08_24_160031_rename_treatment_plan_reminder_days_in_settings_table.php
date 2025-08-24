<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Setting::where('key', 'treatment_plan_reminder_days')->update(['key' => 'treatment_appointment_reminder_days']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::where('key', 'treatment_appointment_reminder_days')->update(['key' => 'treatment_plan_reminder_days']);
    }
};