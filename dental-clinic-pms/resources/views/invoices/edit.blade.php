<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Invoice') }} #{{ $invoice->invoice_number ?? 'INV-' . $invoice->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('invoices.update', $invoice) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Patient Selection -->
                            <div>
                                <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient *</label>
                                <select name="patient_id" id="patient_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id', $invoice->patient_id) == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Appointment Selection -->
                            <div>
                                <label for="appointment_id" class="block text-sm font-medium text-gray-700">Appointment (Optional)</label>
                                <select name="appointment_id" id="appointment_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Appointment</option>
                                    @foreach($appointments as $appointment)
                                        <option value="{{ $appointment->id }}" {{ old('appointment_id', $invoice->appointment_id) == $appointment->id ? 'selected' : '' }}>
                                            {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }} - {{ $appointment->appointment_datetime->format('Y-m-d H:i') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('appointment_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Treatment Plan Selection -->
                            <div>
                                <label for="treatment_plan_id" class="block text-sm font-medium text-gray-700">Treatment Plan (Optional)</label>
                                <select name="treatment_plan_id" id="treatment_plan_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Treatment Plan</option>
                                    @foreach($treatmentPlans as $plan)
                                        <option value="{{ $plan->id }}" {{ old('treatment_plan_id', $invoice->treatment_plan_id) == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->patient->first_name }} {{ $plan->patient->last_name }} - {{ $plan->plan_title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('treatment_plan_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date *</label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('due_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Items</h3>
                            <div id="invoice-items">
                                @if($invoice->invoice_items)
                                    @foreach($invoice->invoice_items as $index => $item)
                                        <div class="invoice-item border rounded-lg p-4 mb-4">
                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Description *</label>
                                                    <input type="text" name="invoice_items[{{ $index }}][description]" value="{{ $item['description'] }}" required
                                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                           placeholder="Service or item description">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                                                    <input type="number" name="invoice_items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}" min="1" required
                                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Unit Price *</label>
                                                    <input type="number" name="invoice_items[{{ $index }}][unit_price]" value="{{ $item['unit_price'] }}" step="0.01" min="0.01" required
                                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                           placeholder="0.00">
                                                </div>
                                                <div class="flex items-end">
                                                    <button type="button" class="remove-item bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="invoice-item border rounded-lg p-4 mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Description *</label>
                                                <input type="text" name="invoice_items[0][description]" required
                                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                       placeholder="Service or item description">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                                                <input type="number" name="invoice_items[0][quantity]" value="1" min="1" required
                                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Unit Price *</label>
                                                <input type="number" name="invoice_items[0][unit_price]" step="0.01" min="0.01" required
                                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                       placeholder="0.00">
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" class="remove-item bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" id="add-item" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Add Item
                            </button>
                        </div>

                        <!-- Total Amount Display -->
                        <div class="mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-medium text-gray-900">Total Amount:</span>
                                    <span id="total-amount" class="text-2xl font-bold text-gray-900">₱{{ number_format($invoice->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('invoices.show', $invoice) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let itemIndex = {{ $invoice->invoice_items ? count($invoice->invoice_items) : 1 }};

        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('invoice-items');
            const newItem = document.createElement('div');
            newItem.className = 'invoice-item border rounded-lg p-4 mb-4';
            newItem.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description *</label>
                        <input type="text" name="invoice_items[${itemIndex}][description]" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Service or item description">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                        <input type="number" name="invoice_items[${itemIndex}][quantity]" value="1" min="1" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit Price *</label>
                        <input type="number" name="invoice_items[${itemIndex}][unit_price]" step="0.01" min="0.01" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="0.00">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="remove-item bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Remove
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
            itemIndex++;
            updateTotal();
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                const items = document.querySelectorAll('.invoice-item');
                if (items.length > 1) {
                    e.target.closest('.invoice-item').remove();
                    updateTotal();
                }
            }
        });

        document.addEventListener('input', function(e) {
            if (e.target.name.includes('quantity') || e.target.name.includes('unit_price')) {
                updateTotal();
            }
        });

        function updateTotal() {
            let total = 0;
            const items = document.querySelectorAll('.invoice-item');
            
            items.forEach(item => {
                const quantity = parseFloat(item.querySelector('input[name*="[quantity]"]').value) || 0;
                const unitPrice = parseFloat(item.querySelector('input[name*="[unit_price]"]').value) || 0;
                total += quantity * unitPrice;
            });
            
            document.getElementById('total-amount').textContent = '₱' + total.toFixed(2);
        }

        // Initialize total
        updateTotal();
    </script>
</x-app-layout>