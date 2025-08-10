<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('email_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('email_templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|max:255|unique:email_templates,type',
            'recipient_type' => 'nullable|in:manual,roles',
            'recipient_emails' => 'nullable|string',
            'recipient_roles' => 'nullable|string',
        ]);

        EmailTemplate::create($validated);

        return redirect()->route('email_templates.index')->with('success', 'Email template created successfully.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('email_templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|max:255|unique:email_templates,type,' . $emailTemplate->id,
            'recipient_type' => 'nullable|in:manual,roles',
            'recipient_emails' => 'nullable|string',
            'recipient_roles' => 'nullable|string',
        ]);

        $emailTemplate->update($validated);

        return redirect()->route('email_templates.index')->with('success', 'Email template updated successfully.');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();

        return redirect()->route('email_templates.index')->with('success', 'Email template deleted successfully.');
    }
}
