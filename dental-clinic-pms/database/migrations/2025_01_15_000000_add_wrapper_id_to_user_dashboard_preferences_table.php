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
        // Add wrapper_id column to existing table
        Schema::table('user_dashboard_preferences', function (Blueprint $table) {
            if (!Schema::hasColumn('user_dashboard_preferences', 'wrapper_id')) {
                $table->integer('wrapper_id')->default(1)->after('height');
            }
        });

        // Create table for storing wrapper information
        if (!Schema::hasTable('user_dashboard_wrappers')) {
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

        // Insert default wrapper for existing users
        if (Schema::hasTable('user_dashboard_wrappers') && Schema::hasTable('user_dashboard_preferences')) {
            $existingUsers = \DB::table('user_dashboard_preferences')
                ->select('user_id')
                ->distinct()
                ->pluck('user_id');

            foreach ($existingUsers as $userId) {
                \DB::table('user_dashboard_wrappers')->insertOrIgnore([
                    'user_id' => $userId,
                    'wrapper_id' => 1,
                    'title' => 'Dashboard 1',
                    'order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_dashboard_preferences', function (Blueprint $table) {
            if (Schema::hasColumn('user_dashboard_preferences', 'wrapper_id')) {
                $table->dropColumn('wrapper_id');
            }
        });
        
        Schema::dropIfExists('user_dashboard_wrappers');
    }
};