<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EmailTemplate;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        EmailTemplate::where('type', 'treatment_plan_reminder')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        EmailTemplate::create([
            'type' => 'treatment_plan_reminder',
            'name' => 'Treatment Plan Reminder',
            'subject' => 'Reminder: Follow-up on your Treatment Plan',
            'body' => '<p>Dear {{patient_name}},</p><p>This is a reminder to follow-up on your treatment plan. Please review your treatment plan and schedule an appointment at your earliest convenience.</p><p><a href="{{treatment_plan_link}}">View Treatment Plan</a></p><p>Thank you!</p>',
        ]);
    }
};