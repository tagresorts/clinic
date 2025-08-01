<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('dentist_id')->constrained('users')->onDelete('cascade');
            
            // Appointment Details
            $table->dateTime('appointment_datetime');
            $table->integer('duration_minutes')->default(30);
            $table->string('appointment_type'); // consultation, cleaning, extraction, etc.
            $table->enum('status', ['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            
            // Notes and Reasons
            $table->text('reason_for_visit')->nullable();
            $table->text('appointment_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Reminder Tracking
            $table->boolean('reminder_sent')->default(false);
            $table->timestamp('reminder_sent_at')->nullable();
            
            // Modification History
            $table->json('modification_history')->nullable(); // Track reschedules/changes
            
            $table->timestamps();
            
            // Indexes
            $table->index('appointment_datetime');
            $table->index(['dentist_id', 'appointment_datetime']);
            $table->index(['patient_id', 'appointment_datetime']);
            $table->index('status');
            
            // Unique constraint to prevent double booking
            $table->unique(['dentist_id', 'appointment_datetime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};