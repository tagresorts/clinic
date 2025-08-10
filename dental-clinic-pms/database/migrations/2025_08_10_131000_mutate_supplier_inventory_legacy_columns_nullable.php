<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('suppliers')) {
            Schema::table('suppliers', function (Blueprint $table) {
                if (Schema::hasColumn('suppliers', 'name')) {
                    $table->string('name')->nullable()->change();
                }
                if (Schema::hasColumn('suppliers', 'contact_person')) {
                    $table->string('contact_person')->nullable()->change();
                }
            });
        }
        if (Schema::hasTable('inventory_items')) {
            Schema::table('inventory_items', function (Blueprint $table) {
                if (Schema::hasColumn('inventory_items', 'name')) {
                    $table->string('name')->nullable()->change();
                }
                if (Schema::hasColumn('inventory_items', 'category')) {
                    $table->string('category')->nullable()->change();
                }
                if (Schema::hasColumn('inventory_items', 'unit_of_measure')) {
                    $table->string('unit_of_measure')->nullable()->change();
                }
                if (Schema::hasColumn('inventory_items', 'unit_price')) {
                    $table->decimal('unit_price', 8, 2)->nullable()->change();
                }
            });
        }
    }

    public function down(): void {}
};
