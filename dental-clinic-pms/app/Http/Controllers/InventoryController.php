<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryItem::with('supplier');
        if ($request->filled('search')) {
            $term = $request->string('search');
            $query->where(function ($q) use ($term) {
                $q->where('item_name', 'like', "%{$term}%")
                  ->orWhere('item_code', 'like', "%{$term}%")
                  ->orWhere('brand', 'like', "%{$term}%");
            });
        }
        if ($request->boolean('low_stock')) {
            $query->whereColumn('quantity_in_stock', '<=', 'reorder_level');
        }
        if ($request->boolean('expiring')) {
            $query->where('has_expiry', true)->whereDate('expiry_date', '<=', now()->addDays(30));
        }
        $items = $query->latest()->paginate(20);
        return view('inventory.index', compact('items'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('supplier_name')->pluck('supplier_name', 'id');
        return view('inventory.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_code' => 'nullable|string|max:100',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'quantity_in_stock' => 'required|integer|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'has_expiry' => 'boolean',
            'expiry_date' => 'nullable|date',
        ]);
        
        $item = InventoryItem::create($validated);
        
        Log::channel('log_viewer')->info("Inventory item '{$item->item_name}' created by " . auth()->user()->name, [
            'item_id' => $item->id,
            'item_code' => $item->item_code,
            'quantity' => $item->quantity_in_stock,
            'unit_cost' => $item->unit_cost
        ]);
        
        return redirect()->route('inventory.show', $item)->with('success', 'Item created.');
    }

    public function show(InventoryItem $inventory)
    {
        return view('inventory.show', ['item' => $inventory->load('supplier')]);
    }

    public function edit(InventoryItem $inventory)
    {
        $suppliers = Supplier::orderBy('supplier_name')->pluck('supplier_name', 'id');
        return view('inventory.edit', ['item' => $inventory, 'suppliers' => $suppliers]);
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_code' => 'nullable|string|max:100',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'quantity_in_stock' => 'required|integer|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'has_expiry' => 'boolean',
            'expiry_date' => 'nullable|date',
        ]);
        
        $oldQty = $inventory->quantity_in_stock;
        $oldName = $inventory->item_name;
        $oldCost = $inventory->unit_cost;
        
        $inventory->update($validated);
        
        // Record stock adjustment when quantity changes
        if (isset($validated['quantity_in_stock']) && $validated['quantity_in_stock'] != $oldQty) {
            $delta = (int)$validated['quantity_in_stock'] - (int)$oldQty;
            if ($delta !== 0) {
                StockMovement::create([
                    'inventory_item_id' => $inventory->id,
                    'type' => $delta > 0 ? 'in' : 'out',
                    'quantity' => abs($delta),
                    'reference' => 'manual_adjustment',
                    'notes' => 'Adjusted via inventory edit',
                    'created_by' => auth()->id(),
                ]);
            }
        }
        
        Log::channel('log_viewer')->info("Inventory item '{$oldName}' updated by " . auth()->user()->name, [
            'item_id' => $inventory->id,
            'old_name' => $oldName,
            'new_name' => $validated['item_name'],
            'old_quantity' => $oldQty,
            'new_quantity' => $validated['quantity_in_stock'],
            'old_cost' => $oldCost,
            'new_cost' => $validated['unit_cost'],
            'quantity_adjustment' => isset($validated['quantity_in_stock']) && $validated['quantity_in_stock'] != $oldQty ? $validated['quantity_in_stock'] - $oldQty : null
        ]);
        
        return redirect()->route('inventory.show', $inventory)->with('success', 'Item updated.');
    }

    public function destroy(InventoryItem $inventory)
    {
        $itemName = $inventory->item_name;
        $itemId = $inventory->id;
        
        $inventory->delete();
        
        Log::channel('log_viewer')->info("Inventory item '{$itemName}' deleted by " . auth()->user()->name, [
            'item_id' => $itemId
        ]);
        
        return redirect()->route('inventory.index')->with('success', 'Item deleted.');
    }
}
