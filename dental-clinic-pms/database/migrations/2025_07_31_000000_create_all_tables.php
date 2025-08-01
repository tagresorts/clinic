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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('receptionist'); // administrator, dentist, receptionist
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->text('allergies')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('medical_notes')->nullable();
            $table->text('dental_history')->nullable();
            $table->text('previous_treatments')->nullable();
            $table->text('dental_notes')->nullable();
            $table->string('insurance_provider')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->string('insurance_group_number')->nullable();
            $table->date('insurance_expiry_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients');
            $table->foreignId('dentist_id')->constrained('users');
            $table->dateTime('appointment_datetime');
            $table->text('reason');
            $table->string('status'); // e.g., scheduled, confirmed, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients');
            $table->foreignId('dentist_id')->constrained('users');
            $table->string('plan_title');
            $table->text('diagnosis');
            $table->decimal('estimated_cost', 10, 2);
            $table->integer('estimated_duration_sessions');
            $table->string('priority'); // e.g., low, medium, high, urgent
            $table->string('status'); // e.g., proposed, patient_approved, in_progress, completed, cancelled
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('treatment_notes')->nullable();
            $table->text('patient_concerns')->nullable();
            $table->text('dentist_notes')->nullable();
            $table->decimal('actual_cost', 10, 2)->nullable();
            $table->boolean('insurance_covered')->default(false);
            $table->decimal('insurance_coverage_amount', 10, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('treatment_plan_procedure', function (Blueprint $table) {
            $table->foreignId('treatment_plan_id')->constrained('treatment_plans')->onDelete('cascade');
            $table->foreignId('procedure_id')->constrained('procedures')->onDelete('cascade');
            $table->primary(['treatment_plan_id', 'procedure_id']);
        });

        Schema::create('treatment_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients');
            $table->foreignId('dentist_id')->constrained('users');
            $table->foreignId('treatment_plan_id')->nullable()->constrained('treatment_plans');
            $table->date('treatment_date');
            $table->text('treatment_notes')->nullable();
            $table->text('post_treatment_instructions')->nullable();
            $table->text('observations')->nullable();
            $table->json('teeth_treated')->nullable();
            $table->json('dental_chart_updates')->nullable();
            $table->json('medications_prescribed')->nullable();
            $table->boolean('follow_up_required')->default(false);
            $table->date('next_visit_recommended')->nullable();
            $table->json('attached_images')->nullable();
            $table->json('attached_documents')->nullable();
            $table->string('treatment_outcome')->nullable();
            $table->text('complications_notes')->nullable();
            $table->decimal('treatment_cost', 10, 2)->nullable();
            $table->boolean('billed')->default(false);
            $table->timestamps();
        });

        Schema::create('treatment_record_procedure', function (Blueprint $table) {
            $table->foreignId('treatment_record_id')->constrained('treatment_records')->onDelete('cascade');
            $table->foreignId('procedure_id')->constrained('procedures')->onDelete('cascade');
            $table->primary(['treatment_record_id', 'procedure_id']);
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients');
            $table->foreignId('appointment_id')->nullable()->constrained('appointments');
            $table->foreignId('treatment_plan_id')->nullable()->constrained('treatment_plans');
            $table->decimal('total_amount', 8, 2);
            $table->decimal('outstanding_balance', 8, 2);
            $table->string('status'); // e.g., pending, sent, paid, partially_paid, overdue, cancelled
            $table->date('due_date');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->decimal('amount', 8, 2);
            $table->string('payment_method');
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->foreignId('received_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('unit_of_measure');
            $table->integer('quantity_in_stock');
            $table->integer('reorder_level');
            $table->decimal('unit_price', 8, 2);
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->date('last_restock_date')->nullable();
            $table->timestamps();
        });

        Schema::create('dental_charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients');
            $table->date('chart_date');
            $table->json('chart_data'); // Store complex chart data as JSON
            $table->text('notes')->nullable();
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('event_type');
            $table->text('description');
            $table->morphs('auditable'); // For polymorphic relations (e.g., Patient, Appointment, etc.)
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('dental_charts');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('treatment_record_procedure');
        Schema::dropIfExists('treatment_records');
        Schema::dropIfExists('treatment_plan_procedure');
        Schema::dropIfExists('treatment_plans');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
