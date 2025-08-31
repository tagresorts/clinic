<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Invoice #{{ $invoice->invoice_number ?? 'INV-' . $invoice->id }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('invoices.edit', $invoice) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Invoice
                </a>
                <a href="{{ route('invoices.pdf', $invoice) }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                    Download PDF
                </a>
                @if($invoice->status !== 'sent')
                    <form action="{{ route('invoices.send', $invoice) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Send Invoice
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Invoice Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Patient Information</h4>
                            <p class="text-sm text-gray-600">
                                <strong>Name:</strong> {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}<br>
                                <strong>Patient ID:</strong> {{ $invoice->patient->patient_id }}<br>
                                <strong>Phone:</strong> {{ $invoice->patient->phone ?? 'N/A' }}<br>
                                <strong>Email:</strong> {{ $invoice->patient->email ?? 'N/A' }}
                            </p>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Invoice Information</h4>
                            <p class="text-sm text-gray-600">
                                <strong>Invoice Number:</strong> {{ $invoice->invoice_number ?? 'INV-' . $invoice->id }}<br>
                                <strong>Created Date:</strong> {{ $invoice->created_at->format('Y-m-d') }}<br>
                                <strong>Due Date:</strong> {{ $invoice->due_date->format('Y-m-d') }}<br>
                                <strong>Created By:</strong> {{ $invoice->createdBy->name }}
                            </p>
                        </div>
                    </div>

                    @if($invoice->appointment)
                    <div class="mt-4">
                        <h4 class="font-medium text-gray-700 mb-2">Related Appointment</h4>
                        <p class="text-sm text-gray-600">
                            <strong>Date:</strong> {{ $invoice->appointment->appointment_datetime->format('Y-m-d H:i') }}<br>
                            <strong>Type:</strong> {{ $invoice->appointment->appointment_type }}<br>
                            <strong>Dentist:</strong> {{ $invoice->appointment->dentist->name }}
                        </p>
                    </div>
                    @endif

                    @if($invoice->treatmentPlan)
                    <div class="mt-4">
                        <h4 class="font-medium text-gray-700 mb-2">Related Treatment Plan</h4>
                        <p class="text-sm text-gray-600">
                            <strong>Title:</strong> {{ $invoice->treatmentPlan->plan_title }}<br>
                            <strong>Status:</strong> {{ ucfirst($invoice->treatmentPlan->status) }}<br>
                            <strong>Estimated Cost:</strong> ${{ number_format($invoice->treatmentPlan->estimated_cost, 2) }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Items</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if($invoice->invoice_items)
                                    @foreach($invoice->invoice_items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $item['description'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item['quantity'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ${{ number_format($item['unit_price'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ${{ number_format($item['quantity'] * $item['unit_price'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No items found
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <div class="text-right">
                            <p class="text-lg font-medium text-gray-900">
                                Total Amount: ${{ number_format($invoice->total_amount, 2) }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Outstanding Balance: ${{ number_format($invoice->outstanding_balance, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Payment History</h3>
                        <a href="{{ route('payments.create') }}?invoice_id={{ $invoice->id }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Record Payment
                        </a>
                    </div>
                    
                    @if($invoice->payments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Received By</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($invoice->payments as $payment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $payment->payment_date->format('Y-m-d') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ${{ number_format($payment->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $payment->payment_reference }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $payment->receivedBy->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('payments.show', $payment) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No payments recorded yet.</p>
                    @endif
                </div>
            </div>

            <!-- Status Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Status Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Invoice Status</h4>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($invoice->status === 'sent') bg-blue-100 text-blue-800
                                @elseif($invoice->status === 'paid') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Payment Status</h4>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($invoice->payment_status === 'paid') bg-green-100 text-green-800
                                @elseif($invoice->payment_status === 'partial') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $invoice->payment_status)) }}
                            </span>
                        </div>
                    </div>

                    @if($invoice->paid_at)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">
                            <strong>Paid Date:</strong> {{ $invoice->paid_at->format('Y-m-d H:i') }}
                        </p>
                    </div>
                    @endif

                    @if($invoice->sent_at)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">
                            <strong>Sent Date:</strong> {{ $invoice->sent_at->format('Y-m-d H:i') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>