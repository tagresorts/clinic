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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id')->unique(); // Auto-generated unique patient ID
            
            // Demographics
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->text('address');
            $table->string('phone');
            $table->string('email')->nullable();
            
            // Emergency Contact
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->string('emergency_contact_relationship');
            
            // Medical History
            $table->text('allergies')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('medical_notes')->nullable();
            
            // Dental History
            $table->text('dental_history')->nullable();
            $table->text('previous_treatments')->nullable();
            $table->text('dental_notes')->nullable();
            
            // Insurance Information
            $table->string('insurance_provider')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->string('insurance_group_number')->nullable();
            $table->date('insurance_expiry_date')->nullable();
            
            // Patient Status
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['first_name', 'last_name']);
            $table->index('date_of_birth');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};