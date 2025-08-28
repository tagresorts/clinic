<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = PurchaseOrder::with('supplier')->latest()->paginate(20);
        return view('purchase_orders.index', compact('orders'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('supplier_name')->pluck('supplier_name', 'id');
        $items = InventoryItem::orderBy('item_name')->pluck('item_name', 'id');
        return view('purchase_orders.create', compact('suppliers', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'lines' => 'required|array|min:1',
            'lines.*.inventory_item_id' => 'nullable|exists:inventory_items,id',
            'lines.*.description' => 'nullable|string',
            'lines.*.quantity_ordered' => 'required|integer|min:1',
            'lines.*.unit_cost' => 'required|numeric|min:0',
        ]);

        $order = null;
        $supplierName = Supplier::find($validated['supplier_id'])->supplier_name;
        
        DB::transaction(function () use ($validated, &$order) {
            $order = PurchaseOrder::create([
                'supplier_id' => $validated['supplier_id'],
                'status' => 'submitted',
                'expected_date' => $validated['expected_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
                'total_cost' => 0,
            ]);

            $total = 0;
            foreach ($validated['lines'] as $line) {
                $lineTotal = (float)$line['quantity_ordered'] * (float)$line['unit_cost'];
                $total += $lineTotal;
                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'inventory_item_id' => $line['inventory_item_id'] ?? null,
                    'description' => $line['description'] ?? null,
                    'quantity_ordered' => $line['quantity_ordered'],
                    'unit_cost' => $line['unit_cost'],
                    'line_total' => $lineTotal,
                ]);
            }

            $order->update(['total_cost' => $total]);
        });

        Log::channel('log_viewer')->info("Purchase Order created by " . auth()->user()->name, [
            'order_id' => $order->id,
            'supplier' => $supplierName,
            'total_cost' => $order->total_cost,
            'line_items' => count($validated['lines']),
            'expected_date' => $validated['expected_date'] ?? 'Not specified'
        ]);

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order created.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.inventoryItem']);
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    public function receive(PurchaseOrder $purchaseOrder)
    {
        // Placeholder: receiving flow would increase stock and create stock movements
        Log::channel('log_viewer')->info("Purchase Order receiving attempted by " . auth()->user()->name, [
            'order_id' => $purchaseOrder->id,
            'supplier' => $purchaseOrder->supplier->supplier_name ?? 'Unknown',
            'status' => 'Not implemented yet'
        ]);
        
        return back()->with('success', 'Receiving not yet implemented.');
    }
}
