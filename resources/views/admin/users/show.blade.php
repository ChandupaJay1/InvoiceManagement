<x-app-layout>
    <x-slot name="header">
        <x-page-header 
            title="User Details: {{ $user->name }}" 
            :backUrl="route('admin.users.index')"
            subtitle="Invoices and performance analysis">
            <x-slot name="actions">
                <a href="{{ route('admin.backup', ['user_id' => $user->id]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 transition ease-in-out duration-150 shadow-sm">
                    Backup User Data
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Daily Sales -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm uppercase font-bold tracking-wider">Daily Sales</div>
                    <div class="text-3xl font-extrabold text-indigo-600 mt-2">${{ number_format($dailySales, 2) }}</div>
                    <div class="text-sm text-gray-400 mt-1">{{ now()->format('F d, Y') }}</div>
                </div>

                <!-- Monthly Sales -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm uppercase font-bold tracking-wider">Monthly Sales</div>
                    <div class="text-3xl font-extrabold text-green-600 mt-2">${{ number_format($monthlySales, 2) }}</div>
                    <div class="text-sm text-gray-400 mt-1">{{ now()->format('F Y') }}</div>
                </div>
            </div>

            <!-- Invoices List -->
            <div class="space-y-8">
                @foreach ($invoices as $month => $monthInvoices)
                    @php
                        $monthTotal = $monthInvoices->sum('total');
                    @endphp
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800">{{ $month }}</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                Total: ${{ number_format($monthTotal, 2) }}
                            </span>
                        </div>
                        <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stoles</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($monthInvoices as $invoice)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 text-sm">
                                                <div class="flex flex-wrap gap-1">
                                                    @if($invoice->stoles)
                                                        @foreach(explode(', ', $invoice->stoles) as $stole)
                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                                {{ $stole }}
                                                            </span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $invoice->customer_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($invoice->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
