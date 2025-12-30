<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monthly Collections by User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if($report->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                    No data available.
                </div>
            @endif

            @foreach ($report as $month => $userStats)
                @php
                    $monthTotal = $userStats->sum('total_amount');
                    $dateObj = \Carbon\Carbon::createFromFormat('Y-m', $month);
                @endphp
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-100 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-bold text-indigo-900">{{ $dateObj->format('F Y') }}</h3>
                            <span class="text-sm text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded-full">{{ $month }}</span>
                        </div>
                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-white text-indigo-700 shadow-sm border border-indigo-100">
                            Month Total: ${{ number_format($monthTotal, 2) }}
                        </span>
                    </div>
                    <div class="p-0 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice Count</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Collection</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($userStats as $stat)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $stat->user->name ?? 'Unknown User' }}</div>
                                            <div class="text-xs text-gray-500">{{ $stat->user->email ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $stat->invoice_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">${{ number_format($stat->total_amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($stat->user)
                                                <a href="{{ route('admin.users.show', $stat->user) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
