<?php

namespace App\Http\Controllers;

use App\Models\SmtpConfiguration;
use Illuminate\Http\Request;

class SmtpConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $smtpConfigurations = SmtpConfiguration::all();
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
            SmtpConfiguration::where('is_default', true)->update(['is_default' => false]);
        }

        SmtpConfiguration::create($request->all());

        return redirect()->route('smtp-configurations.index')->with('success', 'SMTP Configuration created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SmtpConfiguration $smtpConfiguration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SmtpConfiguration $smtpConfiguration)
    {
        return view('smtp-configurations.edit', compact('smtpConfiguration'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SmtpConfiguration $smtpConfiguration)
    {
        $request->validate([
            'host' => 'required',
            'port' => 'required|integer',
            'username' => 'required',
            'password' => 'required',
            'encryption' => 'required',
        ]);

        if ($request->has('is_default')) {
            SmtpConfiguration::where('is_default', true)->update(['is_default' => false]);
        }

        $smtpConfiguration->update($request->all());

        return redirect()->route('smtp-configurations.index')->with('success', 'SMTP Configuration updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SmtpConfiguration $smtpConfiguration)
    {
        $smtpConfiguration->delete();
        return redirect()->route('smtp-configurations.index')->with('success', 'SMTP Configuration deleted successfully.');
    }
}
