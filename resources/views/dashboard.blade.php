<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
            <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Daily Sales -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-600 font-medium">Daily Sales</h3>
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-800">${{ number_format($dailySales, 2) }}</p>
                <p class="text-sm text-gray-500 mt-2">{{ now()->format('F d, Y') }}</p>
            </div>

            <!-- Monthly Sales -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-600 font-medium">Monthly Sales</h3>
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-800">${{ number_format($monthlySales, 2) }}</p>
                <p class="text-sm text-gray-500 mt-2">{{ now()->format('F Y') }}</p>
            </div>

            <!-- Total Invoices -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-600 font-medium">Total Invoices</h3>
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ $totalInvoices }}</p>
                <p class="text-sm text-gray-500 mt-2">All time</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Invoice
                </a>
                <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View All Invoices
                </a>
            </div>
        </div>

        <!-- Recent Invoices -->
        @if($recentInvoices->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Invoices</h3>
                    <a href="{{ route('invoices.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View All →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Invoice #</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($recentInvoices as $invoice)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-indigo-600">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="hover:text-indigo-800">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-800">{{ $invoice->customer_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">${{ number_format($invoice->total, 2) }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">View</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-12 border border-gray-200 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No invoices yet</h3>
                <p class="text-gray-600 mb-4">Get started by creating your first invoice</p>
                <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                    Create Your First Invoice
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
