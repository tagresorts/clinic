<?php

require_once 'vendor/autoload.php';

use App\Models\InventoryItem;
use Carbon\Carbon;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Inventory Alert Logic\n";
echo "============================\n\n";

// Test low stock items
$lowStockCount = InventoryItem::whereColumn('quantity_in_stock', '<=', 'reorder_level')->count();
echo "Low stock items: $lowStockCount\n";

// Test expiring items
$expiringCount = InventoryItem::where('has_expiry', true)
    ->whereDate('expiry_date', '<=', Carbon::today()->addDays(30))
    ->count();
echo "Expiring items: $expiringCount\n\n";

// Show all inventory items
echo "All Inventory Items:\n";
$items = InventoryItem::all();
foreach ($items as $item) {
    echo "- {$item->item_name}: Stock={$item->quantity_in_stock}, Reorder={$item->reorder_level}, Has Expiry=" . ($item->has_expiry ? 'Yes' : 'No');
    if ($item->has_expiry && $item->expiry_date) {
        echo ", Expires={$item->expiry_date}";
    }
    echo "\n";
}

echo "\nExpected Results:\n";
echo "- Low stock items: 2 (Disposable Gloves and Fluoride Varnish)\n";
echo "- Expiring items: 1 (Fluoride Varnish)\n";