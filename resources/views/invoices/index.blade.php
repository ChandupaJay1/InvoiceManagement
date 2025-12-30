<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Invoice History</h2>
                <p class="text-gray-600">View and manage all your invoices</p>
            </div>
            <a href="{{ route('invoices.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Invoice
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            @if($invoices->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Invoice #</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Contact</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($invoices as $invoice)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-indigo-600">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="hover:text-indigo-800">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-800">{{ $invoice->customer_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $invoice->customer_contact }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">${{ number_format($invoice->total, 2) }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">View</a>
                                        <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $invoices->links() }}
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No invoices yet</h3>
                    <p class="text-gray-600 mb-4">Create your first invoice to get started</p>
                    <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        Create Invoice
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
