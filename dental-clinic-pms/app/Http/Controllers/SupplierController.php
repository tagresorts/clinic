<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();
        if ($request->filled('search')) {
            $term = $request->string('search');
            $query->where(function ($q) use ($term) {
                $q->where('supplier_name', 'like', "%{$term}%")
                  ->orWhere('contact_person_name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%");
            });
        }
        $suppliers = $query->latest()->paginate(20);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);
        
        $supplier = Supplier::create($validated);
        
        Log::channel('log_viewer')->info("Supplier '{$supplier->supplier_name}' created by " . auth()->user()->name, [
            'supplier_id' => $supplier->id,
            'email' => $supplier->email,
            'phone' => $supplier->phone
        ]);
        
        return redirect()->route('suppliers.show', $supplier)->with('success', 'Supplier created.');
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);
        
        $oldName = $supplier->supplier_name;
        $oldEmail = $supplier->email;
        $oldPhone = $supplier->phone;
        
        $supplier->update($validated);
        
        Log::channel('log_viewer')->info("Supplier '{$oldName}' updated by " . auth()->user()->name, [
            'supplier_id' => $supplier->id,
            'old_name' => $oldName,
            'new_name' => $validated['supplier_name'],
            'old_email' => $oldEmail,
            'new_email' => $validated['email'],
            'old_phone' => $oldPhone,
            'new_phone' => $validated['phone']
        ]);
        
        return redirect()->route('suppliers.show', $supplier)->with('success', 'Supplier updated.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplierName = $supplier->supplier_name;
        $supplierId = $supplier->id;
        
        $supplier->delete();
        
        Log::channel('log_viewer')->info("Supplier '{$supplierName}' deleted by " . auth()->user()->name, [
            'supplier_id' => $supplierId
        ]);
        
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted.');
    }
}
