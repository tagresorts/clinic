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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            
            // User Information
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('user_name')->nullable(); // Store name even if user is deleted
            $table->string('user_role')->nullable();
            
            // Action Information
            $table->string('action'); // create, read, update, delete, login, logout, etc.
            $table->string('entity_type'); // patient, appointment, treatment_plan, etc.
            $table->unsignedBigInteger('entity_id')->nullable(); // ID of the affected record
            $table->string('entity_description')->nullable(); // Human readable description
            
            // Request Information
            $table->string('ip_address', 45)->nullable(); // Support for IPv6
            $table->text('user_agent')->nullable();
            $table->string('request_method')->nullable(); // GET, POST, PUT, DELETE
            $table->string('request_url')->nullable();
            
            // Change Details
            $table->json('old_values')->nullable(); // Previous state
            $table->json('new_values')->nullable(); // New state
            $table->json('changed_fields')->nullable(); // List of fields that changed
            
            // Additional Context
            $table->text('description')->nullable(); // Human readable description
            $table->json('metadata')->nullable(); // Additional context data
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            // Session Information
            $table->string('session_id')->nullable();
            $table->timestamp('event_time'); // Exact time of the event
            
            // Security Flags
            $table->boolean('is_sensitive_data')->default(false); // HIPAA/GDPR sensitive
            $table->boolean('requires_review')->default(false); // Flagged for review
            
            $table->timestamps();
            
            // Indexes for efficient querying
            $table->index(['user_id', 'event_time']);
            $table->index(['entity_type', 'entity_id']);
            $table->index('action');
            $table->index('event_time');
            $table->index('ip_address');
            $table->index('severity');
            $table->index('is_sensitive_data');
            $table->index('requires_review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};