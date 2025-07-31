<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('contact_person', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status === 'active') {
            $query->where('is_active', true);
        }

        $suppliers = $query->orderBy('name')->paginate(20);

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        // Only administrators can manage suppliers
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can manage suppliers.');
        }

        return view('suppliers.create');
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request)
    {
        // Only administrators can manage suppliers
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can manage suppliers.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'website' => 'nullable|url|max:255',
            'tax_id' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $supplier = Supplier::create($validated);

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('inventoryItems');

        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier)
    {
        // Only administrators can manage suppliers
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can manage suppliers.');
        }

        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        // Only administrators can manage suppliers
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can manage suppliers.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'website' => 'nullable|url|max:255',
            'tax_id' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy(Supplier $supplier)
    {
        // Only administrators can manage suppliers
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can manage suppliers.');
        }

        // Check if supplier has associated inventory items
        if ($supplier->inventoryItems()->count() > 0) {
            return redirect()->route('suppliers.show', $supplier)
                ->with('error', 'Cannot delete supplier with associated inventory items.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}