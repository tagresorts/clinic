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
        Schema::create('treatment_records', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('dentist_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('treatment_plan_id')->nullable()->constrained()->onDelete('set null');
            
            // Treatment Details
            $table->date('treatment_date');
            $table->json('procedures_performed'); // Array of procedures with tooth numbers, details
            $table->text('treatment_notes');
            $table->text('post_treatment_instructions')->nullable();
            $table->text('observations')->nullable();
            
            // Clinical Data
            $table->json('teeth_treated')->nullable(); // Array of tooth numbers
            $table->json('dental_chart_updates')->nullable(); // Updates to dental chart
            $table->text('medications_prescribed')->nullable();
            $table->text('follow_up_required')->nullable();
            $table->date('next_visit_recommended')->nullable();
            
            // Attached Files and Images
            $table->json('attached_images')->nullable(); // X-rays, photos
            $table->json('attached_documents')->nullable(); // Lab reports, etc.
            
            // Treatment Outcome
            $table->enum('treatment_outcome', ['successful', 'partial', 'complications', 'follow_up_needed'])->default('successful');
            $table->text('complications_notes')->nullable();
            
            // Financial
            $table->decimal('treatment_cost', 10, 2)->nullable();
            $table->boolean('billed')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['patient_id', 'treatment_date']);
            $table->index(['dentist_id', 'treatment_date']);
            $table->index('treatment_date');
            $table->index('treatment_outcome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_records');
    }
};