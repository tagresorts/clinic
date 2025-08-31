<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\User;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(['invoice.patient', 'patient', 'receivedBy'])
            ->latest('payment_date')
            ->paginate(15);
        
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $invoices = Invoice::with('patient')
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->get();
        
        $patients = Patient::all();
        $users = User::all();
        
        return view('payments.create', compact('invoices', 'patients', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'patient_id' => 'required|exists:patients,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,credit_card,debit_card,bank_transfer,check,insurance',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_reference' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'check_number' => 'nullable|string|max:255',
            'bank_reference' => 'nullable|string|max:255',
            'card_last_four' => 'nullable|string|max:4',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Create payment
            $payment = Payment::create([
                'invoice_id' => $request->invoice_id,
                'patient_id' => $request->patient_id,
                'user_id' => auth()->id(),
                'payment_reference' => $request->payment_reference ?? 'PAY-' . time(),
                'payment_date' => $request->payment_date,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'check_number' => $request->check_number,
                'bank_reference' => $request->bank_reference,
                'card_last_four' => $request->card_last_four,
                'status' => 'completed',
                'notes' => $request->notes,
                'receipt_generated' => false,
            ]);

            // Update invoice payment status
            $invoice = Invoice::find($request->invoice_id);
            $totalPaid = $invoice->payments()->sum('amount') + $request->amount;
            
            if ($totalPaid >= $invoice->total_amount) {
                $invoice->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'outstanding_balance' => 0
                ]);
            } else {
                $invoice->update([
                    'payment_status' => 'partial',
                    'outstanding_balance' => $invoice->total_amount - $totalPaid
                ]);
            }

            DB::commit();

            Log::channel('log_viewer')->info("Payment created by " . auth()->user()->name, [
                'payment_id' => $payment->id,
                'invoice_id' => $payment->invoice_id,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method
            ]);

            return redirect()->route('payments.index')
                ->with('success', 'Payment recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment creation failed: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Failed to record payment. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::with(['invoice.patient', 'patient', 'receivedBy'])
            ->findOrFail($id);
        
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment = Payment::findOrFail($id);
        $invoices = Invoice::with('patient')->get();
        $patients = Patient::all();
        $users = User::all();
        
        return view('payments.edit', compact('payment', 'invoices', 'patients', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);
        
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'patient_id' => 'required|exists:patients,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,credit_card,debit_card,bank_transfer,check,insurance',
            'payment_date' => 'required|date',
            'payment_reference' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'check_number' => 'nullable|string|max:255',
            'bank_reference' => 'nullable|string|max:255',
            'card_last_four' => 'nullable|string|max:4',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Store old amount for invoice recalculation
            $oldAmount = $payment->amount;
            
            // Update payment
            $payment->update([
                'invoice_id' => $request->invoice_id,
                'patient_id' => $request->patient_id,
                'payment_reference' => $request->payment_reference,
                'payment_date' => $request->payment_date,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'check_number' => $request->check_number,
                'bank_reference' => $request->bank_reference,
                'card_last_four' => $request->card_last_four,
                'notes' => $request->notes,
            ]);

            // Recalculate invoice payment status
            $invoice = Invoice::find($request->invoice_id);
            $totalPaid = $invoice->payments()->sum('amount');
            
            if ($totalPaid >= $invoice->total_amount) {
                $invoice->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'outstanding_balance' => 0
                ]);
            } else {
                $invoice->update([
                    'payment_status' => 'partial',
                    'outstanding_balance' => $invoice->total_amount - $totalPaid
                ]);
            }

            DB::commit();

            Log::channel('log_viewer')->info("Payment updated by " . auth()->user()->name, [
                'payment_id' => $payment->id,
                'old_amount' => $oldAmount,
                'new_amount' => $payment->amount
            ]);

            return redirect()->route('payments.index')
                ->with('success', 'Payment updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment update failed: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Failed to update payment. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        
        try {
            DB::beginTransaction();

            $invoice = $payment->invoice;
            $payment->delete();

            // Recalculate invoice payment status
            $totalPaid = $invoice->payments()->sum('amount');
            
            if ($totalPaid >= $invoice->total_amount) {
                $invoice->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'outstanding_balance' => 0
                ]);
            } elseif ($totalPaid > 0) {
                $invoice->update([
                    'payment_status' => 'partial',
                    'outstanding_balance' => $invoice->total_amount - $totalPaid
                ]);
            } else {
                $invoice->update([
                    'payment_status' => 'unpaid',
                    'outstanding_balance' => $invoice->total_amount
                ]);
            }

            DB::commit();

            Log::channel('log_viewer')->info("Payment deleted by " . auth()->user()->name, [
                'payment_id' => $id,
                'invoice_id' => $invoice->id
            ]);

            return redirect()->route('payments.index')
                ->with('success', 'Payment deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment deletion failed: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to delete payment. Please try again.');
        }
    }

    /**
     * Generate receipt for payment.
     */
    public function generateReceipt(string $id)
    {
        $payment = Payment::with(['invoice.patient', 'patient', 'receivedBy'])
            ->findOrFail($id);
        
        // Update receipt generated status
        $payment->update([
            'receipt_generated' => true,
            'receipt_number' => 'RCP-' . time() . '-' . $payment->id
        ]);

        Log::channel('log_viewer')->info("Receipt generated by " . auth()->user()->name, [
            'payment_id' => $payment->id,
            'receipt_number' => $payment->receipt_number
        ]);

        return back()->with('success', 'Receipt generated successfully.');
    }
}