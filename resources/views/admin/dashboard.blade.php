<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Users -->
                <div class="stat-card">
                    <div class="stat-label">Total Users</div>
                    <div class="stat-value text-indigo-600">{{ $totalUsers }}</div>
                </div>

                <!-- Total Invoices -->
                <div class="stat-card">
                    <div class="stat-label">Total Invoices</div>
                    <div class="stat-value text-green-600">{{ $totalInvoices }}</div>
                </div>

                <!-- Total Collection -->
                <div class="stat-card">
                    <div class="stat-label">Total Collection</div>
                    <div class="stat-value text-blue-600">${{ number_format($totalCollection, 2) }}</div>
                </div>
            </div>

            <div class="admin-card">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('admin.users') }}" class="btn-indigo">
                            Collection by Users
                        </a>
                        <!-- User changed this back to indigo in previous step manually -->
                        <a href="{{ route('admin.reports.monthly') }}" class="btn-indigo">
                            Monthly Collections
                        </a>
                        <a href="{{ route('admin.invoices') }}" class="btn-blue">
                            Invoices History (All)
                        </a>
                        <a href="{{ route('admin.backup') }}" class="btn-gray">
                            Download Full Backup
                        </a>
                    </div>
                </div>
            </div>

            <!-- Restore Data Section -->
            <div class="admin-card mt-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Restore Data</h3>
                    <form action="{{ route('admin.restore') }}" method="POST" enctype="multipart/form-data" class="max-w-xl">
                        @csrf
                        <div class="w-full">
                            <label class="block font-medium text-sm text-gray-700 mb-2" for="backup_file">
                                Upload Backup File (.csv)
                            </label>
                            <div class="flex flex-col sm:flex-row gap-4 items-start">
                                <div class="w-full">
                                    <input type="file" name="backup_file" id="backup_file" accept=".csv, .xlsx, .xls" class="form-input" required>
                                    <p class="mt-1 text-xs text-gray-500">Supported formats: CSV, Excel (auto-converted)</p>
                                </div>
                                <button type="submit" class="w-full sm:w-auto btn-red px-6 py-2" onclick="return confirm('WARNING: This will merge imported data with existing records. Continue?')">
                                    Restore Data
                                </button>
                            </div>
                        </div>
                    </form>
                    @if(session('success'))
                        <div class="mt-4 text-green-600 font-medium">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="mt-4 text-red-600 font-medium">{{ session('error') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
