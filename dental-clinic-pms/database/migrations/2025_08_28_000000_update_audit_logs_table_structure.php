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
        Schema::table('audit_logs', function (Blueprint $table) {
            // Add new columns
            $table->string('user_name')->nullable()->after('user_id');
            $table->string('user_role')->nullable()->after('user_name');
            $table->string('action')->nullable()->after('user_role');
            $table->string('entity_type')->nullable()->after('action');
            $table->unsignedBigInteger('entity_id')->nullable()->after('entity_type');
            $table->text('entity_description')->nullable()->after('entity_id');
            $table->string('request_method')->nullable()->after('user_agent');
            $table->text('request_url')->nullable()->after('request_method');
            $table->json('old_values')->nullable()->after('request_url');
            $table->json('new_values')->nullable()->after('old_values');
            $table->json('changed_fields')->nullable()->after('new_values');
            $table->enum('severity', ['low', 'medium', 'high'])->default('low')->after('changed_fields');
            $table->string('session_id')->nullable()->after('severity');
            $table->timestamp('event_time')->nullable()->after('session_id');
            $table->boolean('is_sensitive_data')->default(false)->after('event_time');
            $table->boolean('requires_review')->default(false)->after('is_sensitive_data');
            $table->json('metadata')->nullable()->after('requires_review');
        });

        // Drop old columns that are no longer needed
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn(['event_type', 'auditable_type', 'auditable_id']);
        });

        // Update existing records to have basic values
        DB::statement("UPDATE audit_logs SET action = 'legacy_event', entity_type = 'Legacy', severity = 'low' WHERE action IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Remove new columns
            $table->dropColumn([
                'user_name', 'user_role', 'action', 'entity_type', 'entity_id', 
                'entity_description', 'request_method', 'request_url', 'old_values', 
                'new_values', 'changed_fields', 'severity', 'session_id', 'event_time', 
                'is_sensitive_data', 'requires_review', 'metadata'
            ]);

            // Restore old columns
            $table->string('event_type')->after('user_id');
            $table->morphs('auditable')->after('event_type');
        });
    }
};