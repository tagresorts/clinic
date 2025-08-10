<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Supplier;
use Illuminate\Http\Request;

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
        $inventory->update($validated);
        return redirect()->route('inventory.show', $inventory)->with('success', 'Item updated.');
    }

    public function destroy(InventoryItem $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Item deleted.');
    }
}
