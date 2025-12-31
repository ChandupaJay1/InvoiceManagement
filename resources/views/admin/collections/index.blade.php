<x-app-layout>
    <x-slot name="header">
        <x-page-header 
            title="Collections by Users" 
            :backUrl="route('admin.dashboard')"
            subtitle="Financial summaries by each staff member" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Summary Boxes -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Total Staff Count</p>
                    <p class="text-3xl font-extrabold text-indigo-600 mt-2">{{ $users->count() }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Today's Grand Total</p>
                    <p class="text-3xl font-extrabold text-green-600 mt-2">${{ number_format($users->sum('today_total'), 2) }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Monthly Grand Total</p>
                    <p class="text-3xl font-extrabold text-blue-600 mt-2">${{ number_format($users->sum('month_total'), 2) }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
                <div class="p-0 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50 uppercase text-[10px] tracking-widest text-gray-500 font-bold">
                                    <th class="px-6 py-4 text-left">Staff Member</th>
                                    <th class="px-6 py-4 text-center">Invoices</th>
                                    <th class="px-6 py-4 text-right">Today</th>
                                    <th class="px-6 py-4 text-right">This Month</th>
                                    <th class="px-6 py-4 text-right">Lifetime</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                                                {{ $user->invoices_count }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-bold text-green-600">
                                            ${{ number_format($user->today_total, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-bold text-blue-600">
                                            ${{ number_format($user->month_total, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-bold text-indigo-600">
                                            ${{ number_format($user->lifetime_total, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center text-xs font-bold uppercase tracking-widest text-indigo-600 hover:text-indigo-900 transition-colors">
                                                History
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 italic">No staff members found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
