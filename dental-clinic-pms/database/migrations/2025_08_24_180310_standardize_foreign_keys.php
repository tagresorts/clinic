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
        Schema::table('invoices', function (Blueprint $table) {
            $table->renameColumn('created_by', 'user_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('received_by', 'user_id');
        });

        Schema::table('dental_charts', function (Blueprint $table) {
            $table->renameColumn('updated_by', 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->renameColumn('user_id', 'created_by');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('user_id', 'received_by');
        });

        Schema::table('dental_charts', function (Blueprint $table) {
            $table->renameColumn('user_id', 'updated_by');
        });
    }
};