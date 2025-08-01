<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\TreatmentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['patient']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->orderBy('invoice_date', 'desc')->paginate(20);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create(Request $request)
    {
        // Only receptionists and administrators can create invoices
        if (!auth()->user()->isAdministrator() && !auth()->user()->isReceptionist()) {
            abort(403, 'Only receptionists and administrators can create invoices.');
        }

        $patient = null;
        if ($request->has('patient_id')) {
            $patient = Patient::findOrFail($request->patient_id);
        }

        $patients = Patient::orderBy('first_name')->get();
        $treatmentRecords = TreatmentRecord::with('patient')
            ->whereDoesntHave('invoice')
            ->orderBy('treatment_date', 'desc')
            ->get();

        return view('invoices.create', compact('patient', 'patients', 'treatmentRecords'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        // Only receptionists and administrators can create invoices
        if (!auth()->user()->isAdministrator() && !auth()->user()->isReceptionist()) {
            abort(403, 'Only receptionists and administrators can create invoices.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'invoice_date' => 'required|date|before_or_equal:today',
            'due_date' => 'required|date|after_or_equal:today',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.treatment_record_id' => 'nullable|exists:treatment_records,id',
            'subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
        ]);

        $invoice = Invoice::create($validated);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['patient', 'payments']);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(Invoice $invoice)
    {
        // Only receptionists and administrators can edit invoices
        if (!auth()->user()->isAdministrator() && !auth()->user()->isReceptionist()) {
            abort(403, 'Only receptionists and administrators can edit invoices.');
        }

        // Only allow editing if not paid
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Cannot edit paid invoices.');
        }

        $patients = Patient::orderBy('first_name')->get();
        $treatmentRecords = TreatmentRecord::with('patient')
            ->whereDoesntHave('invoice')
            ->orWhere('invoice_id', $invoice->id)
            ->orderBy('treatment_date', 'desc')
            ->get();

        return view('invoices.edit', compact('invoice', 'patients', 'treatmentRecords'));
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        // Only receptionists and administrators can edit invoices
        if (!auth()->user()->isAdministrator() && !auth()->user()->isReceptionist()) {
            abort(403, 'Only receptionists and administrators can edit invoices.');
        }

        // Only allow updating if not paid
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Cannot edit paid invoices.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.treatment_record_id' => 'nullable|exists:treatment_records,id',
            'subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
        ]);

        $invoice->update($validated);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice)
    {
        // Only administrators can delete invoices
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can delete invoices.');
        }

        // Only allow deletion if not paid
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Cannot delete paid invoices.');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Send invoice to patient.
     */
    public function send(Invoice $invoice)
    {
        // Only receptionists and administrators can send invoices
        if (!auth()->user()->isAdministrator() && !auth()->user()->isReceptionist()) {
            abort(403, 'Only receptionists and administrators can send invoices.');
        }

        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Only draft invoices can be sent.');
        }

        $invoice->update(['status' => 'sent']);

        // TODO: Send email notification to patient

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice sent successfully.');
    }

    /**
     * Generate PDF for invoice.
     */
    public function generatePdf(Invoice $invoice)
    {
        $invoice->load(['patient', 'payments']);

        // TODO: Generate PDF using a library like DomPDF or Snappy

        return response()->download('path/to/generated/pdf');
    }

    /**
     * Show payments for invoices.
     */
    public function payments(Request $request)
    {
        $query = \App\Models\Payment::with(['invoice.patient']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('invoice.patient', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by payment method
        if ($request->has('payment_method') && !empty($request->payment_method)) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(20);

        return view('payments.index', compact('payments'));
    }

    /**
     * Store a new payment.
     */
    public function storePayment(Request $request)
    {
        // Only receptionists and administrators can create payments
        if (!auth()->user()->isAdministrator() && !auth()->user()->isReceptionist()) {
            abort(403, 'Only receptionists and administrators can create payments.');
        }

        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,credit_card,debit_card,bank_transfer,check,insurance',
            'payment_date' => 'required|date|before_or_equal:today',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,failed,refunded',
        ]);

        // Check if payment amount doesn't exceed invoice balance
        $invoice = Invoice::findOrFail($validated['invoice_id']);
        $totalPaid = $invoice->payments()->sum('amount');
        $remainingBalance = $invoice->total_amount - $totalPaid;

        if ($validated['amount'] > $remainingBalance) {
            return back()->withErrors(['amount' => 'Payment amount cannot exceed the remaining balance of $' . number_format($remainingBalance, 2)]);
        }

        $payment = \App\Models\Payment::create($validated);

        // Update invoice status if fully paid
        if (($totalPaid + $validated['amount']) >= $invoice->total_amount) {
            $invoice->update(['status' => 'paid']);
        } elseif ($invoice->status === 'unpaid') {
            $invoice->update(['status' => 'partial']);
        }

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }
}
