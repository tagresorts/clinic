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
            $table->string('user_name')->nullable()->after('user_id');
            $table->string('user_role')->nullable()->after('user_name');
            $table->string('action')->after('user_role');
            $table->string('entity_type')->nullable()->after('action');
            $table->unsignedBigInteger('entity_id')->nullable()->after('entity_type');
            $table->text('entity_description')->nullable()->after('entity_id');
            $table->string('request_method')->nullable()->after('user_agent');
            $table->string('request_url')->nullable()->after('request_method');
            $table->json('old_values')->nullable()->after('request_url');
            $table->json('new_values')->nullable()->after('old_values');
            $table->json('changed_fields')->nullable()->after('new_values');
            $table->json('metadata')->nullable()->after('description');
            $table->string('severity')->nullable()->after('metadata');
            $table->string('session_id')->nullable()->after('severity');
            $table->timestamp('event_time')->nullable()->after('session_id');
            $table->boolean('is_sensitive_data')->default(false)->after('event_time');
            $table->boolean('requires_review')->default(false)->after('is_sensitive_data');

            $table->dropColumn('event_type');
            $table->dropMorphs('auditable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn('user_name');
            $table->dropColumn('user_role');
            $table->dropColumn('action');
            $table->dropColumn('entity_type');
            $table->dropColumn('entity_id');
            $table->dropColumn('entity_description');
            $table->dropColumn('request_method');
            $table->dropColumn('request_url');
            $table->dropColumn('old_values');
            $table->dropColumn('new_values');
            $table->dropColumn('changed_fields');
            $table->dropColumn('metadata');
            $table->dropColumn('severity');
            $table->dropColumn('session_id');
            $table->dropColumn('event_time');
            $table->dropColumn('is_sensitive_data');
            $table->dropColumn('requires_review');

            $table->string('event_type');
            $table->morphs('auditable');
        });
    }
};
