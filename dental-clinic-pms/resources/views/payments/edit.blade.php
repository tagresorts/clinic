<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Payment') }} #{{ $payment->payment_reference }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('payments.update', $payment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Invoice Selection -->
                            <div>
                                <label for="invoice_id" class="block text-sm font-medium text-gray-700">Invoice *</label>
                                <select name="invoice_id" id="invoice_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Invoice</option>
                                    @foreach($invoices as $invoice)
                                        <option value="{{ $invoice->id }}" {{ old('invoice_id', $payment->invoice_id) == $invoice->id ? 'selected' : '' }}>
                                            {{ $invoice->invoice_number ?? 'INV-' . $invoice->id }} - {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }} (${{ number_format($invoice->outstanding_balance, 2) }} outstanding)
                                        </option>
                                    @endforeach
                                </select>
                                @error('invoice_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Patient Selection -->
                            <div>
                                <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient *</label>
                                <select name="patient_id" id="patient_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id', $payment->patient_id) == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Amount -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount *</label>
                                <input type="number" name="amount" id="amount" step="0.01" min="0.01" required
                                       value="{{ old('amount', $payment->amount) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="0.00">
                                @error('amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method *</label>
                                <select name="payment_method" id="payment_method" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Payment Method</option>
                                    <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="credit_card" {{ old('payment_method', $payment->payment_method) == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="debit_card" {{ old('payment_method', $payment->payment_method) == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                    <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="check" {{ old('payment_method', $payment->payment_method) == 'check' ? 'selected' : '' }}>Check</option>
                                    <option value="insurance" {{ old('payment_method', $payment->payment_method) == 'insurance' ? 'selected' : '' }}>Insurance</option>
                                </select>
                                @error('payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Date -->
                            <div>
                                <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date *</label>
                                <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('payment_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Reference -->
                            <div>
                                <label for="payment_reference" class="block text-sm font-medium text-gray-700">Payment Reference</label>
                                <input type="text" name="payment_reference" id="payment_reference" value="{{ old('payment_reference', $payment->payment_reference) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="Payment reference">
                                @error('payment_reference')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Payment Method Specific Fields -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Details</h3>
                            
                            <!-- Card Payment Fields -->
                            <div id="card-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4" style="display: none;">
                                <div>
                                    <label for="transaction_id" class="block text-sm font-medium text-gray-700">Transaction ID</label>
                                    <input type="text" name="transaction_id" id="transaction_id" value="{{ old('transaction_id', $payment->transaction_id) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Transaction reference number">
                                </div>
                                <div>
                                    <label for="card_last_four" class="block text-sm font-medium text-gray-700">Card Last 4 Digits</label>
                                    <input type="text" name="card_last_four" id="card_last_four" value="{{ old('card_last_four', $payment->card_last_four) }}" maxlength="4"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="1234">
                                </div>
                            </div>

                            <!-- Check Payment Fields -->
                            <div id="check-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4" style="display: none;">
                                <div>
                                    <label for="check_number" class="block text-sm font-medium text-gray-700">Check Number</label>
                                    <input type="text" name="check_number" id="check_number" value="{{ old('check_number', $payment->check_number) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Check number">
                                </div>
                            </div>

                            <!-- Bank Transfer Fields -->
                            <div id="bank-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4" style="display: none;">
                                <div>
                                    <label for="bank_reference" class="block text-sm font-medium text-gray-700">Bank Reference</label>
                                    <input type="text" name="bank_reference" id="bank_reference" value="{{ old('bank_reference', $payment->bank_reference) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Bank reference number">
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Additional notes about this payment">{{ old('notes', $payment->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('payments.show', $payment) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('payment_method').addEventListener('change', function() {
            const method = this.value;
            
            // Hide all method-specific fields
            document.getElementById('card-fields').style.display = 'none';
            document.getElementById('check-fields').style.display = 'none';
            document.getElementById('bank-fields').style.display = 'none';
            
            // Show relevant fields based on payment method
            if (method === 'credit_card' || method === 'debit_card') {
                document.getElementById('card-fields').style.display = 'block';
            } else if (method === 'check') {
                document.getElementById('check-fields').style.display = 'block';
            } else if (method === 'bank_transfer') {
                document.getElementById('bank-fields').style.display = 'block';
            }
        });

        // Auto-populate patient when invoice is selected
        document.getElementById('invoice_id').addEventListener('change', function() {
            const invoiceId = this.value;
            if (invoiceId) {
                // Find the selected invoice and get its patient
                const selectedOption = this.options[this.selectedIndex];
                const patientSelect = document.getElementById('patient_id');
                
                // Extract patient name from the option text and find matching patient
                const optionText = selectedOption.text;
                const patientNameMatch = optionText.match(/- ([^-]+) \(/);
                
                if (patientNameMatch) {
                    const patientName = patientNameMatch[1].trim();
                    const patientNameParts = patientName.split(' ');
                    
                    // Find and select the matching patient
                    for (let option of patientSelect.options) {
                        if (option.value && option.text.includes(patientNameParts[0]) && option.text.includes(patientNameParts[1])) {
                            patientSelect.value = option.value;
                            break;
                        }
                    }
                }
            }
        });

        // Initialize payment method fields
        document.getElementById('payment_method').dispatchEvent(new Event('change'));
    </script>
</x-app-layout>