<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InventoryController extends Controller
{
    /**
     * Display a listing of inventory items.
     */
    public function index(Request $request)
    {
        $query = InventoryItem::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        // Filter by stock status
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('quantity', '>', 0);
                    break;
                case 'low_stock':
                    $query->where('quantity', '<=', 10)->where('quantity', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('quantity', '<=', 0);
                    break;
            }
        }

        $inventoryItems = $query->orderBy('name')->paginate(20);

        return view('inventory.index', compact('inventoryItems'));
    }

    /**
     * Show the form for creating a new inventory item.
     */
    public function create()
    {
        // Only administrators can manage inventory
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can manage inventory.');
        }

        return view('inventory.create');
    }

    /**
     * Store a newly created inventory item in storage.
     */
    public function store(Request $request)
    {
        // Only administrators can manage inventory
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can manage inventory.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|max:100|unique:inventory_items',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'location' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $inventoryItem = InventoryItem::create($validated);

        return redirect()->route('inventory.show', $inventoryItem)
            ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Display the specified inventory item.
     */
    public function show(InventoryItem $inventoryItem)
    {
        $inventoryItem->load('supplier');

        return view('inventory.show', compact('inventoryItem'));
    }

    /**
     * Show the form for editing the specified inventory item.
     */
    public function edit(InventoryItem $inventoryItem)
    {
        // Only administrators can manage inventory
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can manage inventory.');
        }

        return view('inventory.edit', compact('inventoryItem'));
    }

    /**
     * Update the specified inventory item in storage.
     */
    public function update(Request $request, InventoryItem $inventoryItem)
    {
        // Only administrators can manage inventory
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can manage inventory.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|max:100|unique:inventory_items,sku,' . $inventoryItem->id,
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'location' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $inventoryItem->update($validated);

        return redirect()->route('inventory.show', $inventoryItem)
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified inventory item from storage.
     */
    public function destroy(InventoryItem $inventoryItem)
    {
        // Only administrators can manage inventory
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can manage inventory.');
        }

        $inventoryItem->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Update inventory quantity (for stock adjustments).
     */
    public function adjustStock(Request $request, InventoryItem $inventoryItem)
    {
        // Only administrators can manage inventory
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can manage inventory.');
        }

        $validated = $request->validate([
            'adjustment_type' => 'required|in:add,subtract',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $adjustment = $validated['adjustment_type'] === 'add' ? $validated['quantity'] : -$validated['quantity'];
        $inventoryItem->quantity += $adjustment;
        $inventoryItem->save();

        // Log the adjustment
        // TODO: Add audit logging

        return redirect()->route('inventory.show', $inventoryItem)
            ->with('success', 'Stock adjusted successfully.');
    }
}