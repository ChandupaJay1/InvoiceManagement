<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Users -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <div class="text-gray-500 text-sm font-medium uppercase">Total Users</div>
                    <div class="text-3xl font-bold text-indigo-600 mt-2">{{ $totalUsers }}</div>
                </div>

                <!-- Total Invoices -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <div class="text-gray-500 text-sm font-medium uppercase">Total Invoices</div>
                    <div class="text-3xl font-bold text-green-600 mt-2">{{ $totalInvoices }}</div>
                </div>

                <!-- Total Stoles -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <div class="text-gray-500 text-sm font-medium uppercase">Stoles (Total / Payed)</div>
                    <div class="text-3xl font-bold text-purple-600 mt-2">{{ $totalCapacity }} / {{ $totalStoles }}</div>
                </div>

                <!-- Total Collection -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <div class="text-gray-500 text-sm font-medium uppercase">Total Collection</div>
                    <div class="text-3xl font-bold text-blue-600 mt-2">Rs.{{ number_format($totalCollection, 2) }}</div>
                </div>
            </div>

            <!-- Stole Status Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Stole Status Overview</h3>
                            <p class="text-sm text-gray-500 mt-1">Real-time visualization of all 100 stoles</p>
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-4 h-4 rounded bg-white border-2 border-gray-300"></span>
                                <span class="text-gray-600">Paid ({{ count($takenStoles) }})</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-4 h-4 rounded bg-gray-600"></span>
                                <span class="text-gray-600">Available ({{ 100 - count($takenStoles) }})</span>
                            </div>
                        </div>
                    </div>

                    <!-- Two Column Layout: Grid Left, Stats Right (Responsive) -->
                    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-start" x-data="{ page: 1 }">
                        <!-- Left Side: Stole Grid -->
                        <div class="w-full lg:w-auto flex-shrink-0 flex justify-center">
                            <div class="bg-gray-800 p-4 sm:p-6 rounded-xl inline-block">
                                <!-- Pagination Controls -->
                                <div class="flex justify-center gap-2 mb-4">
                                    <button 
                                        type="button" 
                                        @click="page = 1" 
                                        :class="page === 1 ? 'bg-indigo-600 text-white' : 'bg-gray-600 text-gray-300 hover:bg-gray-500'" 
                                        class="px-4 py-2 rounded-lg text-xs font-bold transition-colors"
                                    >
                                        1 - 50
                                    </button>
                                    <button 
                                        type="button" 
                                        @click="page = 2" 
                                        :class="page === 2 ? 'bg-indigo-600 text-white' : 'bg-gray-600 text-gray-300 hover:bg-gray-500'" 
                                        class="px-4 py-2 rounded-lg text-xs font-bold transition-colors"
                                    >
                                        51 - 100
                                    </button>
                                </div>

                                <!-- Stole Grid -->
                                <div class="grid gap-2" style="grid-template-columns: repeat(5, 2.5rem); grid-auto-rows: 2.5rem;">
                                    @for ($i = 1; $i <= 100; $i++)
                                        @php
                                            $isPaid = in_array($i, $takenStoles);
                                        @endphp
                                        <div 
                                            x-show="(page === 1 && {{ $i }} <= 50) || (page === 2 && {{ $i }} > 50)"
                                            class="flex items-center justify-center text-xs font-bold rounded-lg transition-all duration-200
                                                {{ $isPaid 
                                                    ? 'bg-white text-gray-800 shadow-md hover:scale-105' 
                                                    : 'bg-gray-600 text-gray-400 hover:bg-gray-500' 
                                                }}"
                                            title="{{ $isPaid ? 'Stole ' . $i . ' - Paid' : 'Stole ' . $i . ' - Available' }}"
                                        >
                                            {{ $i }}
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- Vertical Separator (Desktop Only) -->
                        <div class="hidden lg:block w-px bg-gray-300 self-stretch"></div>

                        <!-- Right Side: Statistics Cards -->
                        <div class="w-full lg:flex-1 flex flex-col gap-4">
                            <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                                <div class="text-4xl font-bold text-green-600 mb-2">{{ count($takenStoles) }}</div>
                                <div class="text-sm text-gray-600 uppercase tracking-wide">Paid Stoles</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <div class="text-4xl font-bold text-gray-600 mb-2">{{ 100 - count($takenStoles) }}</div>
                                <div class="text-sm text-gray-600 uppercase tracking-wide">Available Stoles</div>
                            </div>
                            <div class="bg-indigo-50 rounded-lg p-6 border border-indigo-200">
                                <div class="text-4xl font-bold text-indigo-600 mb-2">{{ count($takenStoles) > 0 ? round((count($takenStoles) / 100) * 100) : 0 }}%</div>
                                <div class="text-sm text-gray-600 uppercase tracking-wide">Occupancy Rate</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-6">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="{{ route('admin.users.index') }}" class="flex items-center p-6 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200 group">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 mr-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-800">User Management</div>
                                <div class="text-sm text-gray-500">Manage staff and administrators</div>
                            </div>
                        </a>

                        <a href="{{ route('admin.collections') }}" class="flex items-center p-6 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200 group">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 mr-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-800">Collections</div>
                                <div class="text-sm text-gray-500">View collections by staff members</div>
                            </div>
                        </a>

                        <a href="{{ route('admin.reports.monthly') }}" class="flex items-center p-6 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200 group">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 mr-4 group-hover:bg-green-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-800">Reports</div>
                                <div class="text-sm text-gray-500">Monthly sales and performance</div>
                            </div>
                        </a>

                        <a href="{{ route('admin.invoices.index') }}" class="flex items-center p-6 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200 group">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 mr-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-800">All Invoices</div>
                                <div class="text-sm text-gray-500">Full invoice history search</div>
                            </div>
                        </a>

                        <a href="{{ route('admin.backup') }}" class="flex items-center p-6 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200 group">
                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center text-gray-600 mr-4 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-800">Backup Data</div>
                                <div class="text-sm text-gray-500">Export database to CSV</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6 border border-gray-200">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Restore Data</h3>
                    <form action="{{ route('admin.restore') }}" method="POST" enctype="multipart/form-data" class="max-w-xl">
                        @csrf
                        <div class="flex gap-4 items-end">
                            <div class="flex-grow">
                                <label class="block font-medium text-sm text-gray-700 mb-1" for="backup_file">Upload CSV File</label>
                                <input type="file" name="backup_file" id="backup_file" accept=".csv" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('WARNING: This will merge imported data with existing records. Continue?')">
                                Restore
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
