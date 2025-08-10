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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('received_by')->constrained('users')->onDelete('cascade');
            
            // Payment Details
            $table->string('payment_reference')->unique();
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'credit_card', 'debit_card', 'bank_transfer', 'check', 'insurance']);
            
            // Payment Method Specific Information
            $table->string('transaction_id')->nullable(); // For card payments
            $table->string('check_number')->nullable(); // For check payments
            $table->string('bank_reference')->nullable(); // For bank transfers
            $table->string('card_last_four')->nullable(); // Last 4 digits of card
            
            // Status and Verification
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded', 'cancelled'])->default('completed');
            $table->text('notes')->nullable();
            $table->boolean('receipt_generated')->default(false);
            $table->string('receipt_number')->nullable();
            
            // Refund Information
            $table->foreignId('refund_of_payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->text('refund_reason')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['invoice_id', 'payment_date']);
            $table->index(['patient_id', 'payment_date']);
            $table->index('payment_date');
            $table->index('payment_method');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};