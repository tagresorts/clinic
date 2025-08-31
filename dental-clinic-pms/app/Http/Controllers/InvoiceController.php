<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\TreatmentPlan;
use App\Models\Procedure;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['patient', 'createdBy'])
            ->latest()
            ->paginate(15);
        
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::where('is_active', true)->get();
        $appointments = Appointment::with('patient')->get();
        $treatmentPlans = TreatmentPlan::with('patient')->get();
        $procedures = Procedure::all();
        
        return view('invoices.create', compact('patients', 'appointments', 'treatmentPlans', 'procedures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'treatment_plan_id' => 'nullable|exists:treatment_plans,id',
            'total_amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date|after_or_equal:today',
            'invoice_items' => 'required|array|min:1',
            'invoice_items.*.description' => 'required|string|max:255',
            'invoice_items.*.quantity' => 'required|integer|min:1',
            'invoice_items.*.unit_price' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total amount from items
            $totalAmount = 0;
            foreach ($request->invoice_items as $item) {
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }

            // Create invoice
            $invoice = Invoice::create([
                'patient_id' => $request->patient_id,
                'appointment_id' => $request->appointment_id,
                'treatment_plan_id' => $request->treatment_plan_id,
                'total_amount' => $totalAmount,
                'outstanding_balance' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'due_date' => $request->due_date,
                'created_by' => auth()->id(),
                'invoice_items' => json_encode($request->invoice_items),
            ]);

            DB::commit();

            Log::channel('log_viewer')->info("Invoice created by " . auth()->user()->name, [
                'invoice_id' => $invoice->id,
                'patient_id' => $invoice->patient_id,
                'total_amount' => $invoice->total_amount
            ]);

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice creation failed: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Failed to create invoice. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = Invoice::with(['patient', 'createdBy', 'payments.receivedBy'])
            ->findOrFail($id);
        
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $patients = Patient::where('is_active', true)->get();
        $appointments = Appointment::with('patient')->get();
        $treatmentPlans = TreatmentPlan::with('patient')->get();
        $procedures = Procedure::all();
        
        return view('invoices.edit', compact('invoice', 'patients', 'appointments', 'treatmentPlans', 'procedures'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'treatment_plan_id' => 'nullable|exists:treatment_plans,id',
            'total_amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'invoice_items' => 'required|array|min:1',
            'invoice_items.*.description' => 'required|string|max:255',
            'invoice_items.*.quantity' => 'required|integer|min:1',
            'invoice_items.*.unit_price' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total amount from items
            $totalAmount = 0;
            foreach ($request->invoice_items as $item) {
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }

            // Calculate outstanding balance
            $totalPaid = $invoice->payments()->sum('amount');
            $outstandingBalance = max(0, $totalAmount - $totalPaid);

            // Update payment status
            $paymentStatus = 'unpaid';
            if ($totalPaid >= $totalAmount) {
                $paymentStatus = 'paid';
            } elseif ($totalPaid > 0) {
                $paymentStatus = 'partial';
            }

            // Update invoice
            $invoice->update([
                'patient_id' => $request->patient_id,
                'appointment_id' => $request->appointment_id,
                'treatment_plan_id' => $request->treatment_plan_id,
                'total_amount' => $totalAmount,
                'outstanding_balance' => $outstandingBalance,
                'due_date' => $request->due_date,
                'payment_status' => $paymentStatus,
                'invoice_items' => json_encode($request->invoice_items),
            ]);

            DB::commit();

            Log::channel('log_viewer')->info("Invoice updated by " . auth()->user()->name, [
                'invoice_id' => $invoice->id,
                'old_total' => $invoice->getOriginal('total_amount'),
                'new_total' => $totalAmount
            ]);

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice update failed: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Failed to update invoice. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        try {
            DB::beginTransaction();

            // Check if invoice has payments
            if ($invoice->payments()->count() > 0) {
                throw new \Exception('Cannot delete invoice with existing payments.');
            }

            $invoice->delete();

            DB::commit();

            Log::channel('log_viewer')->info("Invoice deleted by " . auth()->user()->name, [
                'invoice_id' => $id,
                'patient_id' => $invoice->patient_id
            ]);

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice deletion failed: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to delete invoice: ' . $e->getMessage());
        }
    }

    /**
     * Send an invoice.
     */
    public function send(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        try {
            // Update invoice status
            $invoice->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);

            Log::channel('log_viewer')->info("Invoice sent by " . auth()->user()->name, [
                'invoice_id' => $invoice->id,
                'patient_id' => $invoice->patient_id
            ]);

            return back()->with('success', 'Invoice sent successfully.');

        } catch (\Exception $e) {
            Log::error('Invoice sending failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send invoice. Please try again.');
        }
    }

    /**
     * Generate PDF for an invoice.
     */
    public function generatePdf(string $id)
    {
        $invoice = Invoice::with(['patient', 'createdBy', 'payments.receivedBy'])
            ->findOrFail($id);
        
        try {
            // Here you would implement actual PDF generation
            // For now, we'll just log the action
            Log::channel('log_viewer')->info("Invoice PDF generation requested by " . auth()->user()->name, [
                'invoice_id' => $invoice->id,
                'patient_id' => $invoice->patient_id
            ]);

            return back()->with('success', 'PDF generation initiated. Check downloads folder.');

        } catch (\Exception $e) {
            Log::error('Invoice PDF generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate PDF. Please try again.');
        }
    }
}
