<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Align suppliers table with current model/controller expectations
        if (Schema::hasTable('suppliers')) {
            Schema::table('suppliers', function (Blueprint $table) {
                if (!Schema::hasColumn('suppliers', 'supplier_name')) {
                    $table->string('supplier_name')->nullable()->after('id');
                }
                if (!Schema::hasColumn('suppliers', 'contact_person_name')) {
                    $table->string('contact_person_name')->nullable()->after('supplier_name');
                }
                if (!Schema::hasColumn('suppliers', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('address');
                }
            });
            // Backfill from existing columns when present
            if (Schema::hasColumn('suppliers', 'name')) {
                DB::statement("UPDATE suppliers SET supplier_name = COALESCE(supplier_name, name)");
            }
            if (Schema::hasColumn('suppliers', 'contact_person')) {
                DB::statement("UPDATE suppliers SET contact_person_name = COALESCE(contact_person_name, contact_person)");
            }
        }

        // Align inventory_items with current model/controller expectations
        if (Schema::hasTable('inventory_items')) {
            Schema::table('inventory_items', function (Blueprint $table) {
                if (!Schema::hasColumn('inventory_items', 'item_name')) {
                    $table->string('item_name')->nullable()->after('id');
                }
                if (!Schema::hasColumn('inventory_items', 'item_code')) {
                    $table->string('item_code')->nullable()->after('item_name');
                }
                if (!Schema::hasColumn('inventory_items', 'brand')) {
                    $table->string('brand')->nullable()->after('item_code');
                }
                if (!Schema::hasColumn('inventory_items', 'unit_cost')) {
                    $table->decimal('unit_cost', 10, 2)->nullable()->after('reorder_level');
                }
                if (!Schema::hasColumn('inventory_items', 'has_expiry')) {
                    $table->boolean('has_expiry')->default(false)->after('unit_cost');
                }
                if (!Schema::hasColumn('inventory_items', 'expiry_date')) {
                    $table->date('expiry_date')->nullable()->after('has_expiry');
                }
                if (!Schema::hasColumn('inventory_items', 'low_stock_alert_sent')) {
                    $table->boolean('low_stock_alert_sent')->default(false)->after('expiry_date');
                }
                if (!Schema::hasColumn('inventory_items', 'low_stock_alert_sent_at')) {
                    $table->timestamp('low_stock_alert_sent_at')->nullable()->after('low_stock_alert_sent');
                }
                if (!Schema::hasColumn('inventory_items', 'expiry_alert_sent')) {
                    $table->boolean('expiry_alert_sent')->default(false)->after('low_stock_alert_sent_at');
                }
                if (!Schema::hasColumn('inventory_items', 'expiry_alert_sent_at')) {
                    $table->timestamp('expiry_alert_sent_at')->nullable()->after('expiry_alert_sent');
                }
            });
            // Backfill names and unit_cost when old columns are present
            if (Schema::hasColumn('inventory_items', 'name')) {
                DB::statement("UPDATE inventory_items SET item_name = COALESCE(item_name, name)");
            }
            if (Schema::hasColumn('inventory_items', 'unit_price')) {
                DB::statement("UPDATE inventory_items SET unit_cost = COALESCE(unit_cost, unit_price)");
            }
        }

        // Stock Movements
        if (!Schema::hasTable('stock_movements')) {
            Schema::create('stock_movements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
                $table->enum('type', ['in', 'out', 'adjustment']);
                $table->integer('quantity');
                $table->string('reference')->nullable();
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // Purchase Orders
        if (!Schema::hasTable('purchase_orders')) {
            Schema::create('purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
                $table->string('status')->default('draft'); // draft, submitted, received, cancelled
                $table->date('expected_date')->nullable();
                $table->decimal('total_cost', 12, 2)->default(0);
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('purchase_order_items')) {
            Schema::create('purchase_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
                $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->nullOnDelete();
                $table->string('description')->nullable();
                $table->integer('quantity_ordered');
                $table->decimal('unit_cost', 10, 2)->default(0);
                $table->decimal('line_total', 12, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('stock_movements');
        // Column changes are not reverted to avoid data loss
    }
};
