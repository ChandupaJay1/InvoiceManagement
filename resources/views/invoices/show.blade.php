<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Invoice Details</h2>
                <p class="text-gray-600">{{ $invoice->invoice_number }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('invoices.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium text-sm">
                    ← Back to Invoices
                </a>
                <button onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 print:shadow-none print:border-0">
            <!-- Invoice Header -->
            <div class="border-b-2 border-gray-200 pb-6 mb-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-800 mb-3">INVOICE</h1>
                        <div class="space-y-1">
                            <p class="text-gray-800 font-semibold">{{ $invoice->invoice_number }}</p>
                            <p class="text-gray-600">Date: {{ $invoice->invoice_date->format('F d, Y') }}</p>
                            <p class="text-gray-600">Created: {{ $invoice->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                    <div class="text-left md:text-right">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Bill To:</h3>
                        <p class="text-lg text-gray-900 font-semibold">{{ $invoice->customer_name }}</p>
                        <p class="text-gray-600">{{ $invoice->customer_contact }}</p>
                    </div>
                </div>
            </div>

            <!-- Invoice Items Table -->
            <div class="mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Place</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Qty</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Price</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($invoice->items as $item)
                            <tr>
                                <td class="px-4 py-4 text-sm text-gray-800">{{ $item->place }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600 text-center">{{ $item->quantity }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600 text-right">${{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-4 text-sm text-gray-900 font-semibold text-right">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Invoice Total -->
            <div class="border-t-2 border-gray-200 pt-6">
                <div class="flex justify-end">
                    <div class="w-full md:w-80">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Subtotal:</span>
                                <span class="text-gray-900 font-semibold">${{ number_format($invoice->total, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Tax (0%):</span>
                                <span class="text-gray-900 font-semibold">$0.00</span>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t-2 border-gray-300">
                                <span class="text-xl font-bold text-gray-800">Total:</span>
                                <span class="text-3xl font-bold text-indigo-600">${{ number_format($invoice->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Footer -->
            <div class="mt-12 pt-6 border-t border-gray-200 text-center text-sm text-gray-500 print:mt-20">
                <p>Thank you for your business!</p>
                <p class="mt-1">Generated by {{ config('app.name') }} on {{ now()->format('F d, Y') }}</p>
            </div>
        </div>

        <!-- Action Buttons (Hidden in Print) -->
        <div class="mt-6 flex flex-wrap gap-3 print:hidden">
            <form method="POST"
                  action="{{ route('invoices.destroy', $invoice) }}"
                  onsubmit="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Invoice
                </button>
            </form>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .max-w-4xl, .max-w-4xl * {
                visibility: visible;
            }
            .max-w-4xl {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            nav, .print\:hidden {
                display: none !important;
            }
            body {
                background: white;
            }
        }
    </style>
</x-app-layout>
