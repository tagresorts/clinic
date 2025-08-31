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
        Schema::table('payments', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('payments', 'payment_reference')) {
                $table->string('payment_reference')->unique()->after('invoice_id');
            }
            
            if (!Schema::hasColumn('payments', 'patient_id')) {
                $table->foreignId('patient_id')->constrained()->after('payment_reference');
            }
            
            if (!Schema::hasColumn('payments', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('payment_method');
            }
            
            if (!Schema::hasColumn('payments', 'check_number')) {
                $table->string('check_number')->nullable()->after('transaction_id');
            }
            
            if (!Schema::hasColumn('payments', 'bank_reference')) {
                $table->string('bank_reference')->nullable()->after('check_number');
            }
            
            if (!Schema::hasColumn('payments', 'card_last_four')) {
                $table->string('card_last_four', 4)->nullable()->after('bank_reference');
            }
            
            if (!Schema::hasColumn('payments', 'status')) {
                $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('completed')->after('card_last_four');
            }
            
            if (!Schema::hasColumn('payments', 'receipt_generated')) {
                $table->boolean('receipt_generated')->default(false)->after('notes');
            }
            
            if (!Schema::hasColumn('payments', 'receipt_number')) {
                $table->string('receipt_number')->nullable()->after('receipt_generated');
            }
            
            if (!Schema::hasColumn('payments', 'refund_of_payment_id')) {
                $table->foreignId('refund_of_payment_id')->nullable()->constrained('payments')->onDelete('set null')->after('receipt_number');
            }
            
            if (!Schema::hasColumn('payments', 'refund_amount')) {
                $table->decimal('refund_amount', 8, 2)->nullable()->after('refund_of_payment_id');
            }
            
            if (!Schema::hasColumn('payments', 'refund_reason')) {
                $table->text('refund_reason')->nullable()->after('refund_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['refund_of_payment_id']);
            $table->dropColumn([
                'payment_reference', 'patient_id', 'transaction_id', 'check_number', 
                'bank_reference', 'card_last_four', 'status', 'receipt_generated', 
                'receipt_number', 'refund_of_payment_id', 'refund_amount', 'refund_reason'
            ]);
        });
    }
};