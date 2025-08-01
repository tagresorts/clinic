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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            
            // Item Information
            $table->string('item_code')->unique(); // SKU or internal code
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('category'); // e.g., 'instruments', 'consumables', 'materials'
            $table->string('subcategory')->nullable(); // e.g., 'hand instruments', 'anesthetics'
            
            // Supply Information
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('supplier_item_code')->nullable(); // Supplier's SKU
            
            // Stock Information
            $table->string('unit_of_measure'); // pcs, boxes, ml, kg, etc.
            $table->integer('quantity_in_stock')->default(0);
            $table->integer('reorder_level')->default(10);
            $table->integer('maximum_stock_level')->nullable();
            $table->integer('minimum_stock_level')->default(0);
            
            // Financial Information
            $table->decimal('unit_cost', 8, 2);
            $table->decimal('selling_price', 8, 2)->nullable();
            $table->date('last_purchase_date')->nullable();
            $table->decimal('last_purchase_cost', 8, 2)->nullable();
            
            // Expiry and Batch Information
            $table->boolean('has_expiry')->default(false);
            $table->date('expiry_date')->nullable();
            $table->string('batch_number')->nullable();
            $table->string('lot_number')->nullable();
            
            // Storage Information
            $table->string('storage_location')->nullable();
            $table->text('storage_requirements')->nullable(); // Temperature, humidity, etc.
            
            // Status and Alerts
            $table->boolean('is_active')->default(true);
            $table->boolean('low_stock_alert_sent')->default(false);
            $table->timestamp('low_stock_alert_sent_at')->nullable();
            $table->boolean('expiry_alert_sent')->default(false);
            $table->timestamp('expiry_alert_sent_at')->nullable();
            
            // Additional Information
            $table->text('notes')->nullable();
            $table->json('specifications')->nullable(); // Technical specifications as JSON
            
            $table->timestamps();
            
            // Indexes
            $table->index('item_name');
            $table->index('category');
            $table->index(['supplier_id', 'item_name']);
            $table->index('quantity_in_stock');
            $table->index('reorder_level');
            $table->index('expiry_date');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};