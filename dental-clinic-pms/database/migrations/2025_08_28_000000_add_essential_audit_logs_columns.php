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
            // Add only the essential columns that don't already exist
            if (!Schema::hasColumn('audit_logs', 'action')) {
                $table->string('action')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'entity_type')) {
                $table->string('entity_type')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'entity_id')) {
                $table->unsignedBigInteger('entity_id')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'severity')) {
                $table->enum('severity', ['low', 'medium', 'high'])->default('low');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            if (Schema::hasColumn('audit_logs', 'action')) {
                $table->dropColumn('action');
            }
            if (Schema::hasColumn('audit_logs', 'entity_type')) {
                $table->dropColumn('entity_type');
            }
            if (Schema::hasColumn('audit_logs', 'entity_id')) {
                $table->dropColumn('entity_id');
            }
            if (Schema::hasColumn('audit_logs', 'severity')) {
                $table->dropColumn('severity');
            }
        });
    }
};