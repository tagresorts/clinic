<?php

namespace App\Http\Controllers;

use App\Models\Procedure;
use Illuminate\Http\Request;

class ProcedureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $procedures = Procedure::latest()->paginate(20);
        return view('procedures.index', compact('procedures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('procedures.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:procedures',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
        ]);

        Procedure::create($validated);

        return redirect()->route('procedures.index')
            ->with('success', 'Procedure created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Procedure $procedure)
    {
        return view('procedures.show', compact('procedure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Procedure $procedure)
    {
        return view('procedures.edit', compact('procedure'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Procedure $procedure)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:procedures,name,' . $procedure->id,
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
        ]);

        $procedure->update($validated);

        return redirect()->route('procedures.index')
            ->with('success', 'Procedure updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Procedure $procedure)
    {
        $procedure->delete();

        return redirect()->route('procedures.index')
            ->with('success', 'Procedure deleted successfully.');
    }
}
