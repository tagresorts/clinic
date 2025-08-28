<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Add only the columns that don't already exist
            if (!Schema::hasColumn('audit_logs', 'user_role')) {
                $table->string('user_role')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'action')) {
                $table->string('action')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'entity_type')) {
                $table->string('entity_type')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'entity_id')) {
                $table->unsignedBigInteger('entity_id')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'entity_description')) {
                $table->text('entity_description')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'request_method')) {
                $table->string('request_method')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'request_url')) {
                $table->text('request_url')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'old_values')) {
                $table->json('old_values')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'new_values')) {
                $table->json('new_values')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'changed_fields')) {
                $table->json('changed_fields')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'severity')) {
                $table->enum('severity', ['low', 'medium', 'high'])->default('low');
            }
            if (!Schema::hasColumn('audit_logs', 'session_id')) {
                $table->string('session_id')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'event_time')) {
                $table->timestamp('event_time')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'is_sensitive_data')) {
                $table->boolean('is_sensitive_data')->default(false);
            }
            if (!Schema::hasColumn('audit_logs', 'requires_review')) {
                $table->boolean('requires_review')->default(false);
            }
            if (!Schema::hasColumn('audit_logs', 'metadata')) {
                $table->json('metadata')->nullable();
            }
        });

        // Drop old columns that are no longer needed (checking if they exist)
        Schema::table('audit_logs', function (Blueprint $table) {
            if (Schema::hasColumn('audit_logs', 'event_type')) {
                $table->dropColumn('event_type');
            }
            if (Schema::hasColumn('audit_logs', 'auditable_type')) {
                $table->dropColumn('auditable_type');
            }
            if (Schema::hasColumn('audit_logs', 'auditable_id')) {
                $table->dropColumn('auditable_id');
            }
        });

        // Update existing records to have basic values (only if the columns exist)
        if (Schema::hasColumn('audit_logs', 'action') && Schema::hasColumn('audit_logs', 'entity_type') && Schema::hasColumn('audit_logs', 'severity')) {
            DB::statement("UPDATE audit_logs SET action = 'legacy_event', entity_type = 'Legacy', severity = 'low' WHERE action IS NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Remove new columns (checking if they exist)
            $columnsToDrop = [
                'user_role', 'action', 'entity_type', 'entity_id', 
                'entity_description', 'request_method', 'request_url', 'old_values', 
                'new_values', 'changed_fields', 'severity', 'session_id', 'event_time', 
                'is_sensitive_data', 'requires_review', 'metadata'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('audit_logs', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Restore old columns (only if they don't exist)
            if (!Schema::hasColumn('audit_logs', 'event_type')) {
                $table->string('event_type');
            }
            if (!Schema::hasColumn('audit_logs', 'auditable_type')) {
                $table->string('auditable_type');
            }
            if (!Schema::hasColumn('audit_logs', 'auditable_id')) {
                $table->unsignedBigInteger('auditable_id');
            }
        });
    }
};