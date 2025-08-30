<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Create suppliers table
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // Create inventory_items table
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name')->nullable();
            $table->string('item_code')->nullable();
            $table->string('brand')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('unit_of_measure')->nullable();
            $table->integer('quantity_in_stock');
            $table->integer('reorder_level');
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->boolean('has_expiry')->default(false);
            $table->date('expiry_date')->nullable();
            $table->boolean('low_stock_alert_sent')->default(false);
            $table->timestamp('low_stock_alert_sent_at')->nullable();
            $table->boolean('expiry_alert_sent')->default(false);
            $table->timestamp('expiry_alert_sent_at')->nullable();
            $table->decimal('unit_price', 8, 2)->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->date('last_restock_date')->nullable();
            $table->timestamps();
            
            $table->foreign('supplier_id')->references('id')->on('suppliers');
        });

        // Insert supplier data
        DB::table('suppliers')->insert([
            'id' => 1,
            'supplier_name' => 'Dental Supplies Co',
            'email' => 'sales@dentalsupplies.example',
            'phone' => '555-0100',
            'address' => '123 Clinic St',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert inventory data that should trigger alerts
        DB::table('inventory_items')->insert([
            [
                'id' => 1,
                'item_name' => 'Disposable Gloves (M)',
                'item_code' => 'GLV-M-100',
                'brand' => 'SafeTouch',
                'supplier_id' => 1,
                'quantity_in_stock' => 50,
                'reorder_level' => 100,
                'unit_cost' => 5.50,
                'has_expiry' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'item_name' => 'Surgical Mask',
                'item_code' => 'MSK-50',
                'brand' => 'AirMed',
                'supplier_id' => 1,
                'quantity_in_stock' => 500,
                'reorder_level' => 200,
                'unit_cost' => 0.20,
                'has_expiry' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'item_name' => 'Fluoride Varnish',
                'item_code' => 'FLV-20',
                'brand' => 'SmilePro',
                'supplier_id' => 1,
                'quantity_in_stock' => 20,
                'reorder_level' => 20,
                'unit_cost' => 12.00,
                'has_expiry' => true,
                'expiry_date' => now()->addDays(25),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('suppliers');
    }
};