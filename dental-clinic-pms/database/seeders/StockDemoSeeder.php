<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class StockDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (Supplier::count() === 0) {
            $supplier = Supplier::create([
                'supplier_name' => 'Dental Supplies Co',
                'email' => 'sales@dentalsupplies.example',
                'phone' => '555-0100',
                'address' => '123 Clinic St',
                
            ]);
        } else {
            $supplier = Supplier::inRandomOrder()->first();
        }

        if (InventoryItem::count() === 0) {
            InventoryItem::create([
                'item_name' => 'Disposable Gloves (M)',
                'item_code' => 'GLV-M-100',
                'brand' => 'SafeTouch',
                'supplier_id' => $supplier->id ?? null,
                'quantity_in_stock' => 50,
                'reorder_level' => 100,
                'unit_cost' => 5.50,
                'has_expiry' => false,
            ]);
            InventoryItem::create([
                'item_name' => 'Surgical Mask',
                'item_code' => 'MSK-50',
                'brand' => 'AirMed',
                'supplier_id' => $supplier->id ?? null,
                'quantity_in_stock' => 500,
                'reorder_level' => 200,
                'unit_cost' => 0.20,
                'has_expiry' => false,
            ]);
            InventoryItem::create([
                'item_name' => 'Fluoride Varnish',
                'item_code' => 'FLV-20',
                'brand' => 'SmilePro',
                'supplier_id' => $supplier->id ?? null,
                'quantity_in_stock' => 20,
                'reorder_level' => 20,
                'unit_cost' => 12.00,
                'has_expiry' => true,
                'expiry_date' => now()->addDays(25),
            ]);
        }
    }
}
