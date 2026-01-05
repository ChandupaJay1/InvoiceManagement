<x-app-layout>
    <x-slot name="header">
        <style>
            @media print {
                @page {
                    size: 80mm auto;
                    margin: 0;
                }
                body {
                    width: 80mm;
                    margin: 0;
                    padding: 0;
                    background: white !important;
                    font-family: 'Courier New', Courier, monospace;
                }
                .print-receipt-container {
                    width: 80mm;
                    padding: 3mm;
                    margin: 0 auto;
                    box-shadow: none !important;
                    border: none !important;
                }
                nav, header, footer, .print-hidden, [role="alert"], .bg-green-100 {
                    display: none !important;
                }
                .max-w-7xl {
                    max-width: none !important;
                    padding: 0 !important;
                    margin: 0 !important;
                }
                .py-12 {
                    padding: 0 !important;
                }
                .bg-white {
                    background: white !important;
                }
                .text-gray-900, .text-gray-600, .text-gray-400 {
                    color: black !important;
                }
                .text-indigo-600 {
                    color: black !important;
                    font-weight: bold;
                }
                table {
                    width: 100% !important;
                    border-collapse: collapse;
                    margin-top: 2mm;
                }
                th, td {
                    padding: 1.5mm 0 !important;
                    border-bottom: 1px dashed black;
                    font-size: 9pt;
                    text-align: left;
                }
                th:last-child, td:last-child {
                    text-align: right;
                }
                .receipt-header {
                    text-align: center;
                    margin-bottom: 4mm;
                    border-bottom: 2px solid black;
                    padding-bottom: 2mm;
                }
                .receipt-header h1 {
                    font-size: 16pt;
                    font-weight: bold;
                    margin: 0;
                    text-transform: uppercase;
                }
                .receipt-divider {
                    border-top: 1px dashed black;
                    margin: 2mm 0;
                }
                .total-row {
                    font-size: 13pt !important;
                    border-top: 2px solid black !important;
                    border-bottom: 2px double black !important;
                }
                .label-mini {
                    font-size: 8pt;
                    font-weight: bold;
                    text-transform: uppercase;
                    display: block;
                    margin-bottom: 0.5mm;
                }
                .print-only {
                    display: block !important;
                }
                .screen-only {
                    display: none !important;
                }
            }
        </style>
        <x-page-header 
            title="Invoice Details: {{ $invoice->invoice_number }}" 
            :backUrl="route('invoices.index')"
            subtitle="View full details and print invoice">
            <x-slot name="actions">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 transition ease-in-out duration-150 shadow-sm print:hidden">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m24 0v-5a2 2 0 012-2h10a2 2 0 012 2v5m-14 0h14"></path></svg>
                    Print Receipt
                </button>
                <a href="{{ route('invoices.edit', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition ease-in-out duration-150 shadow-sm print:hidden">
                    Edit Invoice
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 print-receipt-container">
                <div class="p-6 text-gray-900">
                    <!-- Thermal Receipt Header -->
                    <div class="receipt-header mb-8">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2 print:hidden">Receipt Preview</h2>
                        <h1 class="text-3xl font-extrabold text-gray-900 print:text-[#000]" style="border: none; margin-bottom: 0.5mm;">සති පොළ කිරිඳිවැල</h1>
                        <h2 class="text-lg font-bold text-gray-900 print:text-[#000]" style="border: none; margin-bottom: 1mm;">(ටෙන්ඩර්කරු විමල් මාළු වෙළෙඳසැල)</h2>
                        <p class="text-xl font-bold text-indigo-600 print:text-[#000]" style="margin-bottom: 1mm;">{{ $invoice->invoice_number }}</p>
                        <p class="text-gray-500 print:text-[#000]" style="font-size: 11pt; margin-bottom: 1mm;">{{ $invoice->created_at->format('F d, Y h:i A') }}</p>
                        <p class="text-gray-500 print:text-[#000]" style="font-size: 10pt; margin-bottom: 4mm;">Issued by: {{ $invoice->user->name }}</p>
                        <div class="hidden print:block receipt-divider"></div>
                    </div>

                    <!-- Invoice Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 border-b border-gray-100 pb-8 print:border-b-0 print:mb-2 print:pb-0">
                        <div class="print:mb-4">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 print:hidden">Customer</h3>
                            <span class="hidden print:block label-mini">CUSTOMER:</span>
                            <div class="space-y-3 print:space-y-0">
                                <p class="text-lg font-bold text-gray-800 print:text-sm print:leading-tight">{{ $invoice->customer_name }}</p>
                                <p class="text-gray-600 flex items-center print:text-sm print:leading-tight">
                                    <svg class="w-4 h-4 mr-2 print:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $invoice->customer_contact }}
                                </p>
                                <!-- Secondary info for print as per user image -->
                                <div class="hidden print:block pt-4">
                                    <p class="text-sm">{{ $invoice->created_at->format('d/m/Y h:i A') }}</p>
                                    <p class="text-xs">Issued by: {{ $invoice->user->name }}</p>
                                    <p class="text-sm font-bold border-b border-gray-100 pb-1">{{ $invoice->invoice_number }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-left md:text-right print:hidden">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Order Details</h3>
                            <div class="space-y-3">
                                <p class="text-gray-600">
                                    <span class="font-bold">DATE:</span>
                                    <span>{{ $invoice->created_at->format('M d, Y h:i A') }}</span>
                                </p>
                                <p class="text-gray-600">
                                    <span class="font-bold">INV #:</span>
                                    <span class="font-bold text-indigo-600">{{ $invoice->invoice_number }}</span>
                                </p>
                                <p class="text-gray-600">
                                    <span class="font-bold">ISSUED BY:</span>
                                    <span>{{ $invoice->user->name }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="hidden print:block receipt-divider"></div>

                    <!-- Items Table -->
                    <div class="mb-8 print:mb-2">
                        <table class="min-w-full divide-y divide-gray-200 print:divide-y-0">
                            <thead class="bg-gray-50 print:bg-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase print:text-black print:px-0">කඩ අංකය</th>
                                    {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase print:text-black print:px-0 print:text-center">QTY</th> --}}
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase print:text-black print:px-0">මිල</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 print:divide-y-0">
                                @foreach($invoice->items as $item)
                                    <tr class="print:border-b print:border-dashed print:border-gray-800">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 print:px-0 print:text-xs">Stole {{ $item->place }}</td>
                                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 print:px-0 print:text-xs print:text-center">{{ $item->quantity }}</td> --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right font-medium print:px-0 print:text-xs">Rs.{{ number_format($item->price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <!-- Subtotal & Tax Row -->
                                <tr class="bg-indigo-50/50 print:bg-white border-t border-indigo-100 print:border-t-0">
                                    <td class="px-6 py-2 text-right text-gray-500 uppercase tracking-widest text-[10px] print:text-gray-500 print:px-0 print:font-bold">මුළු මුදල</td>
                                    <td class="px-6 py-2 text-right text-sm font-bold text-gray-900 print:text-black print:px-0">Rs.{{ number_format($invoice->total, 2) }}</td>
                                </tr>
                                <!-- Tax row removed as requested -->
                                {{-- 
                                <tr class="bg-indigo-50/50 print:bg-white">
                                    <td colspan="3" class="px-6 py-2 text-right text-gray-500 uppercase tracking-widest text-[10px] print:text-black print:px-0">Tax (0%)</td>
                                    <td class="px-6 py-2 text-right text-sm font-bold text-gray-900 print:text-black print:px-0">$0.00</td>
                                </tr>
                                --}}
                                <!-- Grand Total Row -->
                                <tr class="border-t-2 border-gray-900 print:border-black print:border-dashed total-row">
                                    <td class="px-6 py-4 text-right text-gray-900 font-bold uppercase tracking-widest text-sm print:text-black print:px-0 print:font-bold">මුදල</td>
                                    <td class="px-6 py-4 text-right text-2xl text-gray-900 font-black print:text-black print:text-2xl print:px-0">Rs.{{ number_format($invoice->total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Thermal Receipt Footer (Only on Print) -->
                    <div class="hidden print-only text-center mt-6">
                        <div class="receipt-divider"></div>
                        <p class="text-sm font-bold" style="margin-top: 2mm;">ස්තුතියි!</p>
                        <p class="text-[10px] mt-1">Software Developed By තරාදි පියස 071*****</p>
                    </div>

                    <!-- Footer Note (Screen Only) -->
                    <div class="text-center text-gray-400 text-xs mt-12 mb-8 print:hidden">
                        <p class="font-bold text-sm mb-1">ස්තුතියි!</p>
                        <p>Software Developed By තරාදි පියස 071*****</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end px-4 print:hidden">
                <p class="text-gray-400 text-xs italic">System generated invoice. No signature required.</p>
            </div>
        </div>
    </div>
</x-app-layout>
