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
        Schema::table('invoices', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('invoices', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])->default('unpaid')->after('status');
            }
            
            if (!Schema::hasColumn('invoices', 'sent_at')) {
                $table->timestamp('sent_at')->nullable()->after('paid_at');
            }
            
            if (!Schema::hasColumn('invoices', 'invoice_items')) {
                $table->json('invoice_items')->nullable()->after('sent_at');
            }
            
            if (!Schema::hasColumn('invoices', 'invoice_number')) {
                $table->string('invoice_number')->unique()->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'sent_at', 'invoice_items', 'invoice_number']);
        });
    }
};