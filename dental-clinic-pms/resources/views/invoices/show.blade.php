<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoice Details') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-20 w-20 bg-indigo-500 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                                INV
                            </div>
                            <div class="ml-6">
                                <h2 class="text-3xl font-bold text-gray-900">Invoice #{{ $invoice->invoice_number ?? 'INV-' . $invoice->id }}</h2>
                                <p class="text-sm text-gray-500">Patient: {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</p>
                                <p class="mt-2 px-3 py-1 text-sm font-semibold rounded-full
                                    @if($invoice->status === 'sent') bg-blue-100 text-blue-800
                                    @elseif($invoice->status === 'paid') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($invoice->status) }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('invoices.edit', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">Edit Invoice</a>
                            <a href="{{ route('invoices.pdf', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">Download PDF</a>
                            @if($invoice->status !== 'sent')
                                <form action="{{ route('invoices.send', $invoice) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">Send Invoice</button>
                                </form>
                            @endif
                            <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Back to List</a>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Left Column -->
                        <div class="md:col-span-2 space-y-8">
                            <!-- Invoice Information Card -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Invoice Information</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Invoice Number</p>
                                        <p class="text-lg text-gray-900">{{ $invoice->invoice_number ?? 'INV-' . $invoice->id }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Created Date</p>
                                        <p class="text-lg text-gray-900">{{ $invoice->created_at->format('F d, Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Due Date</p>
                                        <p class="text-lg text-gray-900">{{ $invoice->due_date->format('F d, Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Created By</p>
                                        <p class="text-lg text-gray-900">{{ $invoice->createdBy->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Total Amount</p>
                                        <p class="text-lg font-semibold text-gray-900">${{ number_format($invoice->total_amount, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Outstanding Balance</p>
                                        <p class="text-lg font-semibold text-gray-900">${{ number_format($invoice->outstanding_balance, 2) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Patient Information Card -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Patient Information</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Patient Name</p>
                                        <p class="text-lg text-gray-900">
                                            <a href="{{ route('patients.show', $invoice->patient) }}" class="text-indigo-600 hover:underline">
                                                {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}
                                            </a>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Patient ID</p>
                                        <p class="text-lg text-gray-900">{{ $invoice->patient->patient_id }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Phone</p>
                                        <p class="text-lg text-gray-900">{{ $invoice->patient->phone ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Email</p>
                                        <p class="text-lg text-gray-900">{{ $invoice->patient->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($invoice->appointment)
                            <!-- Related Appointment Card -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Related Appointment</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Date & Time</p>
                                        <p class="text-lg text-gray-900">{{ $invoice->appointment->appointment_datetime->format('F d, Y g:i A') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Appointment Type</p>
                                        <p class="text-lg text-gray-900">{{ $invoice->appointment->appointment_type }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Dentist</p>
                                        <p class="text-lg text-gray-900">{{ $invoice->appointment->dentist->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Status</p>
                                        <p class="text-lg text-gray-900">{{ ucfirst(str_replace('_', ' ', $invoice->appointment->status)) }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($invoice->treatmentPlan)
                            <!-- Related Treatment Plan Card -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Related Treatment Plan</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Plan Title</p>
                                        <p class="text-lg text-gray-900">{{ $invoice->treatmentPlan->plan_title }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Status</p>
                                        <p class="text-lg text-gray-900">{{ ucfirst($invoice->treatmentPlan->status) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Estimated Cost</p>
                                        <p class="text-lg text-gray-900">${{ number_format($invoice->treatmentPlan->estimated_cost, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Invoice Items Card -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Invoice Items</h3>
                                
                                @if($invoice->invoice_items)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($invoice->invoice_items as $item)
                                                    <tr>
                                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $item['description'] }}
                                                        </td>
                                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {{ $item['quantity'] }}
                                                        </td>
                                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            ${{ number_format($item['unit_price'], 2) }}
                                                        </td>
                                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            ${{ number_format($item['quantity'] * $item['unit_price'], 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-6 flex justify-end">
                                        <div class="text-right">
                                            <p class="text-lg font-semibold text-gray-900">
                                                Total Amount: ${{ number_format($invoice->total_amount, 2) }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                Outstanding Balance: ${{ number_format($invoice->outstanding_balance, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">No items found</p>
                                @endif
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-8">
                            <!-- Status Information Card -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Status Information</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Invoice Status</p>
                                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full 
                                            @if($invoice->status === 'sent') bg-blue-100 text-blue-800
                                            @elseif($invoice->status === 'paid') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Payment Status</p>
                                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full 
                                            @if($invoice->payment_status === 'paid') bg-green-100 text-green-800
                                            @elseif($invoice->payment_status === 'partial') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $invoice->payment_status)) }}
                                        </span>
                                    </div>

                                    @if($invoice->paid_at)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Paid Date</p>
                                        <p class="text-lg text-gray-900">{{ $invoice->paid_at->format('F d, Y g:i A') }}</p>
                                    </div>
                                    @endif

                                    @if($invoice->sent_at)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Sent Date</p>
                                        <p class="text-lg text-gray-900">{{ $invoice->sent_at->format('F d, Y g:i A') }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Payment History Card -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-semibold text-gray-800">Payment History</h3>
                                    <a href="{{ route('payments.create') }}?invoice_id={{ $invoice->id }}" 
                                       class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Record Payment
                                    </a>
                                </div>
                                
                                @if($invoice->payments->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($invoice->payments as $payment)
                                            <div class="bg-white p-3 rounded border">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">${{ number_format($payment->amount, 2) }}</p>
                                                        <p class="text-xs text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</p>
                                                        <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                                                    </div>
                                                    <a href="{{ route('payments.show', $payment) }}" class="text-indigo-600 hover:text-indigo-900 text-xs">View</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">No payments recorded yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>