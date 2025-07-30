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
        Schema::create('dental_charts', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            
            // Tooth Information
            $table->integer('tooth_number'); // FDI notation (11-18, 21-28, 31-38, 41-48)
            $table->string('tooth_type')->nullable(); // incisor, canine, premolar, molar
            $table->enum('tooth_surface', ['occlusal', 'mesial', 'distal', 'buccal', 'lingual', 'incisal', 'cervical'])->nullable();
            
            // Current Condition
            $table->enum('current_condition', [
                'healthy', 'decay', 'filling', 'crown', 'bridge', 'implant', 
                'root_canal', 'extraction_needed', 'missing', 'impacted', 
                'fractured', 'abscessed', 'mobile'
            ])->default('healthy');
            
            // Detailed Information
            $table->text('condition_details')->nullable();
            $table->enum('filling_material', ['amalgam', 'composite', 'gold', 'ceramic', 'other'])->nullable();
            $table->text('restoration_notes')->nullable();
            $table->date('treatment_date')->nullable();
            
            // Clinical Observations
            $table->enum('mobility', ['none', 'grade_1', 'grade_2', 'grade_3'])->default('none');
            $table->integer('probing_depth_mesial')->nullable(); // in mm
            $table->integer('probing_depth_distal')->nullable(); // in mm
            $table->integer('probing_depth_buccal')->nullable(); // in mm
            $table->integer('probing_depth_lingual')->nullable(); // in mm
            $table->boolean('bleeding_on_probing')->default(false);
            $table->boolean('plaque_present')->default(false);
            $table->boolean('calculus_present')->default(false);
            
            // Color and Appearance
            $table->string('tooth_color')->nullable(); // For cosmetic tracking
            $table->text('appearance_notes')->nullable();
            
            // Associated Records
            $table->foreignId('treatment_record_id')->nullable()->constrained()->onDelete('set null');
            $table->json('attached_images')->nullable(); // X-rays, photos specific to this tooth
            
            // Status
            $table->boolean('requires_attention')->default(false);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('low');
            $table->text('dentist_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['patient_id', 'tooth_number']);
            $table->index(['patient_id', 'current_condition']);
            $table->index('tooth_number');
            $table->index('current_condition');
            $table->index('requires_attention');
            $table->index('priority');
            
            // Unique constraint to prevent duplicate entries for same patient/tooth
            $table->unique(['patient_id', 'tooth_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dental_charts');
    }
};