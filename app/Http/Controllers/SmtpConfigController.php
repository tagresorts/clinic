<?php

namespace App\Http\Controllers;

use App\Models\SmtpConfig;
use Illuminate\Http\Request;

class SmtpConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $smtpConfigurations = SmtpConfig::all();
        return view('smtp-configurations.index', compact('smtpConfigurations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('smtp-configurations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'host' => 'required',
            'port' => 'required|integer',
            'username' => 'required',
            'password' => 'required',
            'encryption' => 'required',
        ]);

        if ($request->has('is_default')) {
            SmtpConfig::where('is_default', true)->update(['is_default' => false]);
        }

        SmtpConfig::create($request->all());

        return redirect()->route('smtp-configurations.index')->with('success', 'SMTP Configuration created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SmtpConfig $smtpConfiguration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SmtpConfig $smtpConfiguration)
    {
        return view('smtp-configurations.edit', compact('smtpConfiguration'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SmtpConfig $smtpConfiguration)
    {
        $request->validate([
            'host' => 'required',
            'port' => 'required|integer',
            'username' => 'required',
            'password' => 'required',
            'encryption' => 'required',
        ]);

        if ($request->has('is_default')) {
            SmtpConfig::where('is_default', true)->update(['is_default' => false]);
        }

        $smtpConfiguration->update($request->all());

        return redirect()->route('smtp-configurations.index')->with('success', 'SMTP Configuration updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SmtpConfig $smtpConfiguration)
    {
        $smtpConfiguration->delete();
        return redirect()->route('smtp-configurations.index')->with('success', 'SMTP Configuration deleted successfully.');
    }
}
