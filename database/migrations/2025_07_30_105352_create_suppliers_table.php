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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            
            // Supplier Information
            $table->string('supplier_name');
            $table->string('company_registration_number')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('state_province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country');
            
            // Contact Information
            $table->string('contact_person_name');
            $table->string('contact_person_title')->nullable();
            $table->string('phone');
            $table->string('mobile')->nullable();
            $table->string('email');
            $table->string('website')->nullable();
            
            // Business Details
            $table->text('products_services')->nullable(); // Types of products/services provided
            $table->enum('payment_terms', ['net_15', 'net_30', 'net_45', 'net_60', 'cash_on_delivery', 'advance_payment'])->default('net_30');
            $table->decimal('credit_limit', 10, 2)->nullable();
            $table->text('notes')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->enum('preferred_supplier', ['yes', 'no'])->default('no');
            
            $table->timestamps();
            
            // Indexes
            $table->index('supplier_name');
            $table->index('is_active');
            $table->index('preferred_supplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};