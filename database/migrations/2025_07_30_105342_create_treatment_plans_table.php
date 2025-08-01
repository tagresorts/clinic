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
        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('dentist_id')->constrained('users')->onDelete('cascade');
            
            // Treatment Plan Details
            $table->string('plan_title');
            $table->text('diagnosis');
            $table->json('proposed_procedures'); // Array of procedures with details
            $table->decimal('estimated_cost', 10, 2);
            $table->integer('estimated_duration_sessions')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Status and Approval
            $table->enum('status', ['proposed', 'patient_approved', 'in_progress', 'completed', 'cancelled'])->default('proposed');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Notes and Comments
            $table->text('treatment_notes')->nullable();
            $table->text('patient_concerns')->nullable();
            $table->text('dentist_notes')->nullable();
            
            // Financial Information
            $table->decimal('actual_cost', 10, 2)->nullable();
            $table->boolean('insurance_covered')->default(false);
            $table->decimal('insurance_coverage_amount', 10, 2)->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['patient_id', 'status']);
            $table->index(['dentist_id', 'status']);
            $table->index('status');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_plans');
    }
};