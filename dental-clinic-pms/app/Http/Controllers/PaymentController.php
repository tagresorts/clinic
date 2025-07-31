<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['invoice.patient', 'invoice']);

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

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(20);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Request $request)
    {
        // Only receptionists and administrators can create payments
        if (!auth()->user()->isAdministrator() && !auth()->user()->isReceptionist()) {
            abort(403, 'Only receptionists and administrators can create payments.');
        }

        $invoice = null;
        if ($request->has('invoice_id')) {
            $invoice = Invoice::with('patient')->findOrFail($request->invoice_id);
        }

        $invoices = Invoice::with('patient')
            ->where('status', '!=', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payments.create', compact('invoice', 'invoices'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
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

        $payment = Payment::create($validated);

        // Update invoice status if fully paid
        if (($totalPaid + $validated['amount']) >= $invoice->total_amount) {
            $invoice->update(['status' => 'paid']);
        } elseif ($invoice->status === 'unpaid') {
            $invoice->update(['status' => 'partial']);
        }

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load(['invoice.patient']);

        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        // Only administrators can edit payments
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can edit payments.');
        }

        $invoices = Invoice::with('patient')->orderBy('created_at', 'desc')->get();

        return view('payments.edit', compact('payment', 'invoices'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        // Only administrators can edit payments
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can edit payments.');
        }

        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,credit_card,debit_card,bank_transfer,check,insurance',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,failed,refunded',
        ]);

        // Recalculate invoice status after payment update
        $oldInvoice = $payment->invoice;
        $newInvoice = Invoice::findOrFail($validated['invoice_id']);

        $payment->update($validated);

        // Update old invoice status
        if ($oldInvoice) {
            $this->updateInvoiceStatus($oldInvoice);
        }

        // Update new invoice status
        $this->updateInvoiceStatus($newInvoice);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        // Only administrators can delete payments
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can delete payments.');
        }

        $invoice = $payment->invoice;
        $payment->delete();

        // Update invoice status
        if ($invoice) {
            $this->updateInvoiceStatus($invoice);
        }

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    /**
     * Update invoice status based on payments.
     */
    private function updateInvoiceStatus(Invoice $invoice)
    {
        $totalPaid = $invoice->payments()->sum('amount');
        
        if ($totalPaid >= $invoice->total_amount) {
            $invoice->update(['status' => 'paid']);
        } elseif ($totalPaid > 0) {
            $invoice->update(['status' => 'partial']);
        } else {
            $invoice->update(['status' => 'unpaid']);
        }
    }
}