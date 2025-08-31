<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use App\Models\TreatmentPlan;
use App\Models\Procedure;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = Patient::all();
        $users = User::all();
        $appointments = Appointment::all();
        $treatmentPlans = TreatmentPlan::all();
        $procedures = Procedure::all();

        if ($patients->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No patients or users found. Please run PatientSeeder and UserSeeder first.');
            return;
        }

        $invoiceItems = [
            [
                'procedure' => 'Dental Cleaning',
                'description' => 'Professional dental cleaning and examination',
                'quantity' => 1,
                'unit_price' => 150.00,
                'total' => 150.00
            ],
            [
                'procedure' => 'Cavity Filling',
                'description' => 'Composite filling for cavity',
                'quantity' => 1,
                'unit_price' => 200.00,
                'total' => 200.00
            ],
            [
                'procedure' => 'Root Canal',
                'description' => 'Root canal treatment',
                'quantity' => 1,
                'unit_price' => 800.00,
                'total' => 800.00
            ],
            [
                'procedure' => 'Crown',
                'description' => 'Dental crown placement',
                'quantity' => 1,
                'unit_price' => 1200.00,
                'total' => 1200.00
            ],
            [
                'procedure' => 'X-Ray',
                'description' => 'Dental X-ray examination',
                'quantity' => 2,
                'unit_price' => 75.00,
                'total' => 150.00
            ]
        ];

        $statuses = ['draft', 'sent', 'paid'];
        $paymentStatuses = ['unpaid', 'partial', 'paid'];

        for ($i = 1; $i <= 20; $i++) {
            $patient = $patients->random();
            $user = $users->random();
            $appointment = $appointments->isNotEmpty() ? $appointments->random() : null;
            $treatmentPlan = $treatmentPlans->isNotEmpty() ? $treatmentPlans->random() : null;
            
            $status = $statuses[array_rand($statuses)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            
            // Generate random invoice items
            $items = [];
            $totalAmount = 0;
            $numItems = rand(1, 3);
            
            for ($j = 0; $j < $numItems; $j++) {
                $item = $invoiceItems[array_rand($invoiceItems)];
                $items[] = $item;
                $totalAmount += $item['total'];
            }

            $outstandingBalance = $paymentStatus === 'paid' ? 0 : 
                                ($paymentStatus === 'partial' ? $totalAmount * 0.3 : $totalAmount);

            $invoice = Invoice::create([
                'patient_id' => $patient->id,
                'appointment_id' => $appointment?->id,
                'treatment_plan_id' => $treatmentPlan?->id,
                'total_amount' => $totalAmount,
                'outstanding_balance' => $outstandingBalance,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'due_date' => Carbon::now()->addDays(rand(7, 30)),
                'user_id' => $user->id,
                'paid_at' => $paymentStatus === 'paid' ? Carbon::now()->subDays(rand(1, 10)) : null,
                'sent_at' => $status === 'sent' || $status === 'paid' ? Carbon::now()->subDays(rand(1, 15)) : null,
                'invoice_items' => $items,
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT),
            ]);

            $this->command->info("Created invoice: {$invoice->invoice_number} for {$patient->first_name} {$patient->last_name}");
        }

        $this->command->info('Invoice seeder completed successfully!');
    }
}
