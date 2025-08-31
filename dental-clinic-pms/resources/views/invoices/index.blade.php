<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoices') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
                        <h3 class="text-2xl font-bold text-gray-900 hidden sm:block">Invoice Management</h3>
                        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 w-full sm:w-auto">
                            <form action="{{ route('invoices.index') }}" method="GET" class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                                <x-text-input id="search" name="search" type="text" class="block w-full sm:w-48" placeholder="Search by patient..." value="{{ request('search') }}" />
                                <select name="status" class="block w-full sm:w-40 border-gray-300 rounded-md">
                                    <option value="">All Status</option>
                                    <option value="draft" @selected(request('status')==='draft')>Draft</option>
                                    <option value="sent" @selected(request('status')==='sent')>Sent</option>
                                    <option value="paid" @selected(request('status')==='paid')>Paid</option>
                                </select>
                                <select name="payment_status" class="block w-full sm:w-40 border-gray-300 rounded-md">
                                    <option value="">All Payment Status</option>
                                    <option value="unpaid" @selected(request('payment_status')==='unpaid')>Unpaid</option>
                                    <option value="partial" @selected(request('payment_status')==='partial')>Partial</option>
                                    <option value="paid" @selected(request('payment_status')==='paid')>Paid</option>
                                </select>
                                <x-primary-button class="w-full sm:w-auto">
                                    {{ __('Filter') }}
                                </x-primary-button>
                                @if(request('search') || request('status') || request('payment_status'))
                                    <a href="{{ route('invoices.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Clear</a>
                                @endif
                            </form>
                            <a href="{{ route('invoices.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto sm:ms-4">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Create Invoice
                            </a>
                            <!-- Columns visibility control -->
                            <div class="relative">
                                <button id="invoices-columns-toggle" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Columns
                                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.114l3.71-3.883a.75.75 0 111.08 1.04l-4.24 4.44a.75.75 0 01-1.08 0l-4.24-4.44a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                                </button>
                                <div id="invoices-columns-menu" class="hidden absolute right-0 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none p-3 z-20">
                                    <p class="text-xs text-gray-500 mb-2">Show/Hide columns</p>
                                    <div class="space-y-2" id="invoices-columns-checkboxes"></div>
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
                        <table id="invoices-table" class="min-w-full divide-y divide-gray-200" data-prefs-url="{{ route('preferences.table.store') }}">
                            <thead class="bg-gray-50">
                                <tr id="invoices-table-header">
                                    <th data-col="invoice_number" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Invoice #</th>
                                    <th data-col="patient" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Patient</th>
                                    <th data-col="date" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Date</th>
                                    <th data-col="due_date" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Due Date</th>
                                    <th data-col="total_amount" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Total Amount</th>
                                    <th data-col="outstanding" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Outstanding</th>
                                    <th data-col="status" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Status</th>
                                    <th data-col="payment_status" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Payment Status</th>
                                    <th data-col="actions" scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($invoices as $invoice)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                        <td data-col="invoice_number" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $invoice->invoice_number ?? 'INV-' . $invoice->id }}
                                        </td>
                                        <td data-col="patient" class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}
                                            </div>
                                        </td>
                                        <td data-col="date" class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-700">{{ $invoice->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td data-col="due_date" class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-700">{{ $invoice->due_date->format('M d, Y') }}</div>
                                        </td>
                                        <td data-col="total_amount" class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">${{ number_format($invoice->total_amount, 2) }}</div>
                                        </td>
                                        <td data-col="outstanding" class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">${{ number_format($invoice->outstanding_balance, 2) }}</div>
                                        </td>
                                        <td data-col="status" class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($invoice->status === 'sent') bg-blue-100 text-blue-800
                                                @elseif($invoice->status === 'paid') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                        <td data-col="payment_status" class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($invoice->payment_status === 'paid') bg-green-100 text-green-800
                                                @elseif($invoice->payment_status === 'partial') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $invoice->payment_status)) }}
                                            </span>
                                        </td>
                                        <td data-col="actions" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('invoices.show', $invoice) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">View</a>
                                                <a href="{{ route('invoices.edit', $invoice) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Edit</a>
                                                @if($invoice->status !== 'sent')
                                                    <form action="{{ route('invoices.send', $invoice) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">Send</button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('invoices.pdf', $invoice) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">PDF</a>
                                                @if($invoice->payments()->count() === 0)
                                                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" 
                                                                onclick="return confirm('Are you sure you want to delete this invoice?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No invoices found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>