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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->softDeletes();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->softDeletes();
        });

        Schema::table('smtp_configs', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
            $table->dropSoftDeletes();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
            $table->dropSoftDeletes();
        });

        Schema::table('smtp_configs', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
            $table->dropSoftDeletes();
        });
    }
};