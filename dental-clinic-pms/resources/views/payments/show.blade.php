<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Payment #{{ $payment->payment_reference }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('payments.edit', $payment) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Payment
                </a>
                @if(!$payment->receipt_generated)
                    <form action="{{ route('payments.receipt', $payment) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Generate Receipt
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Payment Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Patient Information</h4>
                            <p class="text-sm text-gray-600">
                                <strong>Name:</strong> {{ $payment->patient->first_name }} {{ $payment->patient->last_name }}<br>
                                <strong>Patient ID:</strong> {{ $payment->patient->patient_id }}<br>
                                <strong>Phone:</strong> {{ $payment->patient->phone ?? 'N/A' }}<br>
                                <strong>Email:</strong> {{ $payment->patient->email ?? 'N/A' }}
                            </p>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Payment Information</h4>
                            <p class="text-sm text-gray-600">
                                <strong>Payment Reference:</strong> {{ $payment->payment_reference }}<br>
                                <strong>Payment Date:</strong> {{ $payment->payment_date->format('Y-m-d') }}<br>
                                <strong>Amount:</strong> ₱{{ number_format($payment->amount, 2) }}<br>
                                <strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4 class="font-medium text-gray-700 mb-2">Related Invoice</h4>
                        <p class="text-sm text-gray-600">
                            <strong>Invoice Number:</strong> 
                            <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $payment->invoice->invoice_number ?? 'INV-' . $payment->invoice->id }}
                            </a><br>
                                                            <strong>Invoice Total:</strong> ₱{{ number_format($payment->invoice->total_amount, 2) }}<br>
                                <strong>Outstanding Balance:</strong> ₱{{ number_format($payment->invoice->outstanding_balance, 2) }}<br>
                            <strong>Payment Status:</strong> 
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($payment->invoice->payment_status === 'paid') bg-green-100 text-green-800
                                @elseif($payment->invoice->payment_status === 'partial') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $payment->invoice->payment_status)) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment Method Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Method Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($payment->transaction_id)
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Transaction Information</h4>
                            <p class="text-sm text-gray-600">
                                <strong>Transaction ID:</strong> {{ $payment->transaction_id }}<br>
                                @if($payment->card_last_four)
                                    <strong>Card Last 4 Digits:</strong> {{ $payment->card_last_four }}
                                @endif
                            </p>
                        </div>
                        @endif

                        @if($payment->check_number)
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Check Information</h4>
                            <p class="text-sm text-gray-600">
                                <strong>Check Number:</strong> {{ $payment->check_number }}
                            </p>
                        </div>
                        @endif

                        @if($payment->bank_reference)
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Bank Transfer Information</h4>
                            <p class="text-sm text-gray-600">
                                <strong>Bank Reference:</strong> {{ $payment->bank_reference }}
                            </p>
                        </div>
                        @endif
                    </div>

                    @if($payment->notes)
                    <div class="mt-4">
                        <h4 class="font-medium text-gray-700 mb-2">Notes</h4>
                        <p class="text-sm text-gray-600">{{ $payment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Receipt Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Receipt Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Receipt Status</h4>
                            <p class="text-sm text-gray-600">
                                <strong>Receipt Generated:</strong> 
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($payment->receipt_generated) bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $payment->receipt_generated ? 'Yes' : 'No' }}
                                </span>
                            </p>
                            @if($payment->receipt_number)
                            <p class="text-sm text-gray-600 mt-2">
                                <strong>Receipt Number:</strong> {{ $payment->receipt_number }}
                            </p>
                            @endif
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Payment Processing</h4>
                            <p class="text-sm text-gray-600">
                                <strong>Received By:</strong> {{ $payment->receivedBy->name }}<br>
                                <strong>Recorded On:</strong> {{ $payment->created_at->format('Y-m-d H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Refund Information -->
            @if($payment->refund_amount)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Refund Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Refund Details</h4>
                            <p class="text-sm text-gray-600">
                                <strong>Refund Amount:</strong> ₱{{ number_format($payment->refund_amount, 2) }}<br>
                                <strong>Refund Reason:</strong> {{ $payment->refund_reason ?? 'N/A' }}
                            </p>
                        </div>
                        
                        @if($payment->refundOfPayment)
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Original Payment</h4>
                            <p class="text-sm text-gray-600">
                                <strong>Original Payment:</strong> 
                                <a href="{{ route('payments.show', $payment->refundOfPayment) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $payment->refundOfPayment->payment_reference }}
                                </a><br>
                                <strong>Original Amount:</strong> ₱{{ number_format($payment->refundOfPayment->amount, 2) }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>