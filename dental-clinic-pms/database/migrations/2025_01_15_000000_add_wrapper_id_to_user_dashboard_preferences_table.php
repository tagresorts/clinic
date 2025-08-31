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
        Schema::table('user_dashboard_preferences', function (Blueprint $table) {
            $table->integer('wrapper_id')->default(1)->after('height');
        });

        // Create table for storing wrapper information
        Schema::create('user_dashboard_wrappers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('wrapper_id');
            $table->string('title')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'wrapper_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_dashboard_preferences', function (Blueprint $table) {
            $table->dropColumn('wrapper_id');
        });
        
        Schema::dropIfExists('user_dashboard_wrappers');
    }
};