<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $invoices = Invoice::whereIn('payment_status', ['partial', 'paid'])->get();
        $users = User::all();

        if ($invoices->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No invoices with partial/paid status or users found. Please run InvoiceSeeder and UserSeeder first.');
            return;
        }

        $paymentMethods = ['cash', 'credit_card', 'debit_card', 'check', 'bank_transfer'];
        $paymentReferences = [
            'cash' => 'CASH-',
            'credit_card' => 'CC-',
            'debit_card' => 'DC-',
            'check' => 'CHK-',
            'bank_transfer' => 'BT-'
        ];

        $paymentCount = 0;
        $maxPayments = 30;

        foreach ($invoices as $invoice) {
            if ($paymentCount >= $maxPayments) break;

            $patient = $invoice->patient;
            $user = $users->random();
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            
            // Determine payment amount based on invoice status
            if ($invoice->payment_status === 'paid') {
                // Create full payment
                $amount = $invoice->total_amount;
                $paymentCount++;
                
                $this->createPayment($invoice, $patient, $user, $paymentMethod, $amount, $paymentReferences);
            } elseif ($invoice->payment_status === 'partial') {
                // Create partial payment (70% of total)
                $amount = $invoice->total_amount * 0.7;
                $paymentCount++;
                
                $this->createPayment($invoice, $patient, $user, $paymentMethod, $amount, $paymentReferences);
            }
        }

        // Create some additional payments for invoices that might have multiple payments
        $remainingInvoices = $invoices->where('payment_status', 'partial')->take(5);
        foreach ($remainingInvoices as $invoice) {
            if ($paymentCount >= $maxPayments) break;

            $patient = $invoice->patient;
            $user = $users->random();
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            
            // Create second payment for partial invoices
            $remainingAmount = $invoice->outstanding_balance;
            if ($remainingAmount > 0) {
                $amount = $remainingAmount * 0.8; // 80% of remaining amount
                $paymentCount++;
                
                $this->createPayment($invoice, $patient, $user, $paymentMethod, $amount, $paymentReferences);
            }
        }

        $this->command->info('Payment seeder completed successfully!');
    }

    private function createPayment($invoice, $patient, $user, $paymentMethod, $amount, $paymentReferences)
    {
        $paymentDate = Carbon::now()->subDays(rand(1, 30));
        $reference = $paymentReferences[$paymentMethod] . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

        $paymentData = [
            'invoice_id' => $invoice->id,
            'patient_id' => $patient->id,
            'received_by' => $user->id,
            'payment_reference' => $reference,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'payment_date' => $paymentDate,
            'status' => 'completed',
            'receipt_generated' => true,
            'receipt_number' => 'RCP-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
        ];

        // Add method-specific fields
        switch ($paymentMethod) {
            case 'credit_card':
            case 'debit_card':
                $paymentData['card_last_four'] = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                $paymentData['transaction_id'] = 'TXN-' . strtoupper(uniqid());
                break;
            case 'check':
                $paymentData['check_number'] = 'CHK' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
                break;
            case 'bank_transfer':
                $paymentData['bank_reference'] = 'BANK-' . strtoupper(uniqid());
                break;
        }

        $payment = Payment::create($paymentData);

        // Update invoice payment status and outstanding balance
        $totalPaid = $invoice->payments()->sum('amount');
        $outstandingBalance = max(0, $invoice->total_amount - $totalPaid);
        
        $newPaymentStatus = $outstandingBalance == 0 ? 'paid' : 
                           ($totalPaid > 0 ? 'partial' : 'unpaid');

        $invoice->update([
            'payment_status' => $newPaymentStatus,
            'outstanding_balance' => $outstandingBalance,
            'paid_at' => $newPaymentStatus === 'paid' ? $paymentDate : null
        ]);

        $this->command->info("Created payment: {$payment->payment_reference} for {$patient->first_name} {$patient->last_name} - \${$amount}");
    }
}
