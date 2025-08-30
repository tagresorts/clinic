<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class InventoryAlertsSeeder extends Seeder
{
    public function run(): void
    {
        // Create supplier if it doesn't exist
        $supplier = Supplier::firstOrCreate(
            ['supplier_name' => 'Dental Supplies Co'],
            [
                'email' => 'sales@dentalsupplies.example',
                'phone' => '555-0100',
                'address' => '123 Clinic St',
            ]
        );

        // Clear existing inventory items for testing
        InventoryItem::truncate();

        // Create inventory items that should trigger alerts
        InventoryItem::create([
            'item_name' => 'Disposable Gloves (M)',
            'item_code' => 'GLV-M-100',
            'brand' => 'SafeTouch',
            'supplier_id' => $supplier->id,
            'quantity_in_stock' => 50,
            'reorder_level' => 100,
            'unit_cost' => 5.50,
            'has_expiry' => false,
        ]);

        InventoryItem::create([
            'item_name' => 'Surgical Mask',
            'item_code' => 'MSK-50',
            'brand' => 'AirMed',
            'supplier_id' => $supplier->id,
            'quantity_in_stock' => 500,
            'reorder_level' => 200,
            'unit_cost' => 0.20,
            'has_expiry' => false,
        ]);

        InventoryItem::create([
            'item_name' => 'Fluoride Varnish',
            'item_code' => 'FLV-20',
            'brand' => 'SmilePro',
            'supplier_id' => $supplier->id,
            'quantity_in_stock' => 20,
            'reorder_level' => 20,
            'unit_cost' => 12.00,
            'has_expiry' => true,
            'expiry_date' => now()->addDays(25),
        ]);

        echo "Inventory data seeded successfully!\n";
        echo "Expected alerts:\n";
        echo "- Low stock items: 2 (Disposable Gloves and Fluoride Varnish)\n";
        echo "- Expiring items: 1 (Fluoride Varnish)\n";
    }
}