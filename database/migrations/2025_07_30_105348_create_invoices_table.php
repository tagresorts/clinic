<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            // Invoice Details
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->date('due_date');
            
            // Financial Information
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_rate', 5, 4)->default(0); // e.g., 0.0825 for 8.25%
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('outstanding_balance', 10, 2);
            
            // Status and Payment
            $table->enum('status', ['draft', 'sent', 'paid', 'partially_paid', 'overdue', 'cancelled'])->default('draft');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])->default('unpaid');
            
            // Invoice Items (stored as JSON for flexibility)
            $table->json('line_items'); // Array of {description, quantity, unit_price, total}
            
            // Notes and Terms
            $table->text('notes')->nullable();
            $table->text('terms_and_conditions')->nullable();
            
            // Insurance Information
            $table->boolean('insurance_claim_submitted')->default(false);
            $table->string('insurance_claim_number')->nullable();
            $table->decimal('insurance_covered_amount', 10, 2)->nullable();
            $table->decimal('patient_responsibility', 10, 2)->nullable();
            
            // Timestamps
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['patient_id', 'status']);
            $table->index('invoice_date');
            $table->index('due_date');
            $table->index('status');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};