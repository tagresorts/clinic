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
        Schema::create('default_dashboard_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('widget_key');
            $table->integer('x_pos');
            $table->integer('y_pos');
            $table->integer('width');
            $table->integer('height');
            $table->boolean('is_visible')->default(true);
            $table->integer('wrapper_id')->default(1);
            $table->timestamps();

            $table->unique(['widget_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_dashboard_layouts');
    }
};