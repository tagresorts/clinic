<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payments') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
                        <h3 class="text-2xl font-bold text-gray-900 hidden sm:block">Payment Records</h3>
                        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 w-full sm:w-auto">
                            <form action="{{ route('payments.index') }}" method="GET" class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                                <x-text-input id="search" name="search" type="text" class="block w-full sm:w-48" placeholder="Search by patient..." value="{{ request('search') }}" />
                                <select name="payment_method" class="block w-full sm:w-40 border-gray-300 rounded-md">
                                    <option value="">All Methods</option>
                                    <option value="cash" @selected(request('payment_method')==='cash')>Cash</option>
                                    <option value="credit_card" @selected(request('payment_method')==='credit_card')>Credit Card</option>
                                    <option value="debit_card" @selected(request('payment_method')==='debit_card')>Debit Card</option>
                                    <option value="check" @selected(request('payment_method')==='check')>Check</option>
                                    <option value="bank_transfer" @selected(request('payment_method')==='bank_transfer')>Bank Transfer</option>
                                </select>
                                <x-primary-button class="w-full sm:w-auto">
                                    {{ __('Filter') }}
                                </x-primary-button>
                                @if(request('search') || request('payment_method'))
                                    <a href="{{ route('payments.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Clear</a>
                                @endif
                            </form>
                            <a href="{{ route('payments.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto sm:ms-4">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Record Payment
                            </a>
                            <!-- Columns visibility control -->
                            <div class="relative">
                                <button id="payments-columns-toggle" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Columns
                                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.114l3.71-3.883a.75.75 0 111.08 1.04l-4.24 4.44a.75.75 0 01-1.08 0l-4.24-4.44a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                                </button>
                                <div id="payments-columns-menu" class="hidden absolute right-0 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none p-3 z-20">
                                    <p class="text-xs text-gray-500 mb-2">Show/Hide columns</p>
                                    <div class="space-y-2" id="payments-columns-checkboxes"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="hidden sm:block overflow-x-auto shadow-md rounded-lg border border-gray-200">
                        <table id="payments-table" class="min-w-full divide-y divide-gray-200" data-prefs-url="{{ route('preferences.table.store') }}">
                            <thead class="bg-gray-50">
                                <tr id="payments-table-header">
                                    <th data-col="payment_ref" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Payment #</th>
                                    <th data-col="patient" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Patient</th>
                                    <th data-col="invoice" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Invoice #</th>
                                    <th data-col="date" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Date</th>
                                    <th data-col="amount" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Amount</th>
                                    <th data-col="method" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Method</th>
                                    <th data-col="reference" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Reference</th>
                                    <th data-col="received_by" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Received By</th>
                                    <th data-col="actions" scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($payments as $payment)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                        <td data-col="payment_ref" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $payment->payment_reference }}
                                        </td>
                                        <td data-col="patient" class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $payment->patient->first_name }} {{ $payment->patient->last_name }}
                                            </div>
                                        </td>
                                        <td data-col="invoice" class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $payment->invoice->invoice_number ?? 'INV-' . $payment->invoice->id }}
                                            </a>
                                        </td>
                                        <td data-col="date" class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-700">{{ $payment->payment_date->format('M d, Y') }}</div>
                                        </td>
                                        <td data-col="amount" class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">${{ number_format($payment->amount, 2) }}</div>
                                        </td>
                                        <td data-col="method" class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                            </span>
                                        </td>
                                        <td data-col="reference" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $payment->payment_reference }}
                                        </td>
                                        <td data-col="received_by" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $payment->receivedBy->name }}
                                        </td>
                                        <td data-col="actions" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('payments.show', $payment) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">View</a>
                                                <a href="{{ route('payments.edit', $payment) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Edit</a>
                                                @if(!$payment->receipt_generated)
                                                    <form action="{{ route('payments.receipt', $payment) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">Receipt</button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" 
                                                            onclick="return confirm('Are you sure you want to delete this payment?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No payments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
