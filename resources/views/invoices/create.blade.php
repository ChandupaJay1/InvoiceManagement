<x-app-layout>
    <x-slot name="header">
        <x-page-header 
            title="Create New Invoice" 
            :backUrl="route('invoices.index')"
            subtitle="Fill in the details to generate a new invoice" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label for="customer_name" class="block font-medium text-sm text-gray-700">Customer Name (නම)*</label>
                                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div>

                            <div>
                                <label for="customer_contact" class="block font-medium text-sm text-gray-700">Contact (දුරකථන අංකය)</label>
                                <input type="text" name="customer_contact" id="customer_contact" value="{{ old('customer_contact') }}" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div>

                            <div>
                                <label for="invoice_date" class="block font-medium text-sm text-gray-700">Invoice Date (දිනය)*</label>
                                <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', $date) }}" onchange="refreshTakenStoles()" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div>
                        </div>

                        <!-- Stole Chooser (Date Picker Style Popover) -->
                        <div class="mb-8" x-data="{ 
                            open: false,
                            selected: [],
                            page: 1,
                            init() {
                                this.selected = Array.from(selectedStoles);
                            },
                            toggle(id) {
                                toggleStole(id);
                                this.selected = Array.from(selectedStoles);
                            }
                        }" @refresh-stoles.window="selected = Array.from(selectedStoles)">
                            <label class="block font-medium text-sm text-gray-700 mb-2">Choose Stoles (කඩ කාමර තෝරන්න)</label>
                            
                            <div class="relative">
                                <!-- Trigger Button -->
                                <button type="button" 
                                        @click="open = !open"
                                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Select Stoles (<span x-text="selected.length">0</span> selected)
                                    <svg class="w-4 h-4 ml-2 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Popover Content -->
                                <div x-show="open" 
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute z-50 mt-2 min-w-[480px] bg-white rounded-xl shadow-2xl border border-gray-200 p-4 origin-top-left"
                                     style="display: none; width: 480px;">
                                    
                                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                                        <h4 class="font-bold text-gray-800">Select Stoles (1-600)</h4>
                                        <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>

                                    <div class="overflow-y-auto max-h-[320px] p-1" style="display: grid; grid-template-columns: repeat(10, 1fr); gap: 6px;">
                                        @for ($i = 1; $i <= 600; $i++)
                                            @php $isTaken = in_array((string)$i, $takenStoles); @endphp
                                            <button type="button" 
                                                    id="stole-btn-{{ $i }}" 
                                                    x-show="(page === 1 && {{ $i }} >= 1 && {{ $i }} <= 50) || 
                                                            (page === 2 && {{ $i }} >= 51 && {{ $i }} <= 100) ||
                                                            (page === 3 && {{ $i }} >= 101 && {{ $i }} <= 150) ||
                                                            (page === 4 && {{ $i }} >= 151 && {{ $i }} <= 200) ||
                                                            (page === 5 && {{ $i }} >= 201 && {{ $i }} <= 250) ||
                                                            (page === 6 && {{ $i }} >= 251 && {{ $i }} <= 300) ||
                                                            (page === 7 && {{ $i }} >= 301 && {{ $i }} <= 350) ||
                                                            (page === 8 && {{ $i }} >= 351 && {{ $i }} <= 400) ||
                                                            (page === 9 && {{ $i }} >= 401 && {{ $i }} <= 450) ||
                                                            (page === 10 && {{ $i }} >= 451 && {{ $i }} <= 500) ||
                                                            (page === 11 && {{ $i }} >= 501 && {{ $i }} <= 550) ||
                                                            (page === 12 && {{ $i }} >= 551 && {{ $i }} <= 600)"
                                                    @click="toggle({{ $i }})"
                                                    {{ $isTaken ? 'disabled' : '' }}
                                                    class="w-10 h-10 flex items-center justify-center text-sm font-bold rounded-lg transition-all duration-200 border"
                                                    :class="selected.includes({{ $i }}) 
                                                        ? 'bg-indigo-600 text-white border-indigo-600 scale-105 shadow-md' 
                                                        : ({{ $isTaken ? 'true' : 'false' }} 
                                                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-100' 
                                                            : 'bg-white border-gray-200 text-gray-700 hover:border-indigo-400 hover:bg-indigo-50')">
                                                {{ $i }}
                                            </button>
                                        @endfor
                                    </div>

                                    <!-- Pagination Controls -->
                                    <div class="mt-4 flex justify-center gap-2 border-t pt-3 flex-wrap">
                                        <button type="button" @click="page = 1" :class="page === 1 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded text-xs font-bold transition-colors">1-50</button>
                                        <button type="button" @click="page = 2" :class="page === 2 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded text-xs font-bold transition-colors">51-100</button>
                                        <button type="button" @click="page = 3" :class="page === 3 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded text-xs font-bold transition-colors">101-150</button>
                                        <button type="button" @click="page = 4" :class="page === 4 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded text-xs font-bold transition-colors">151-200</button>
                                        <button type="button" @click="page = 5" :class="page === 5 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded text-xs font-bold transition-colors">201-250</button>
                                        <button type="button" @click="page = 6" :class="page === 6 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded text-xs font-bold transition-colors">251-300</button>
                                        <button type="button" @click="page = 7" :class="page === 7 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded text-xs font-bold transition-colors">301-350</button>
                                        <button type="button" @click="page = 8" :class="page === 8 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded text-xs font-bold transition-colors">351-400</button>
                                        <button type="button" @click="page = 9" :class="page === 9 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded text-xs font-bold transition-colors">401-450</button>
                                        <button type="button" @click="page = 10" :class="page === 10 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded text-xs font-bold transition-colors">451-500</button>
                                        <button type="button" @click="page = 11" :class="page === 11 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded text-xs font-bold transition-colors">501-550</button>
                                        <button type="button" @click="page = 12" :class="page === 12 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-1 rounded text-xs font-bold transition-colors">551-600</button>
                                    </div>

                                    <div class="mt-4 pt-4 border-t flex justify-between items-center">
                                        <span class="text-xs text-gray-500">Legend: <span class="inline-block w-2 h-2 rounded-full bg-gray-200 mr-1"></span>Taken <span class="inline-block w-2 h-2 rounded-full bg-indigo-600 ml-2 mr-1"></span>Selected</span>
                                        <button type="button" @click="open = false" class="bg-indigo-600 text-white px-3 py-1.5 rounded-md text-xs font-bold hover:bg-indigo-700">Done</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Selected Badges -->
                            <div class="mt-3 flex flex-wrap gap-2">
                                <template x-for="stoleId in selected" :key="stoleId">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold border border-indigo-200">
                                        Stole <span x-text="stoleId"></span>
                                        <button type="button" @click="toggle(stoleId)" class="hover:text-indigo-900">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </span>
                                </template>
                            </div>
                        </div>

                        <hr class="my-10 border-gray-200">


                        <!-- Items Table -->
                        <div class="mb-8">
                            <h3 class="text-lg font-bold mb-4">Invoice Items</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">කඩ අංකය</th>
                                            {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th> --}}
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">මිල</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsContainer" class="bg-white divide-y divide-gray-200">
                                        <!-- Dynamic Rows -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-50 font-bold">
                                            <td class="px-6 py-4 text-right">මුදල:</td>
                                            <td class="px-6 py-4">Rs.<span id="totalAmount">0.00</span></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Create Invoice</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let takenStoles = @json($takenStoles).map(s => String(s));
        let selectedStoles = new Set();
        let rowIndex = 0;

        async function refreshTakenStoles() {
            const date = document.getElementById('invoice_date').value;
            if (!date) return;

            try {
                const response = await fetch(`{{ route('invoices.stoles.taken') }}?date=${date}`);
                takenStoles = (await response.json()).map(s => String(s));
                
                for (let i = 1; i <= 600; i++) {
                    const btn = document.getElementById(`stole-btn-${i}`);
                    const isTaken = takenStoles.includes(String(i));
                    const isSelected = selectedStoles.has(i);

                    btn.disabled = isTaken;
                    btn.className = 'stole-btn py-2 text-sm font-bold rounded transition-colors ';
                    
                    if (isTaken) {
                        btn.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                        if (isSelected) toggleStole(i);
                    } else if (isSelected) {
                        btn.classList.add('bg-indigo-600', 'text-white');
                    } else {
                        btn.classList.add('bg-white', 'border', 'border-gray-300', 'text-gray-700', 'hover:bg-indigo-50');
                    }
                }
                window.dispatchEvent(new CustomEvent('refresh-stoles'));
            } catch (error) {
                console.error('Failed to fetch taken stoles:', error);
            }
        }

        function toggleStole(id) {
            // Note: In the new UI, Alpine handles visuals, but we still need the underlying logic
            // for the items table and form data.
            
            if (selectedStoles.has(id)) {
                // Standard toggle logic:
                selectedStoles.delete(id);
                const row = document.getElementById(`row-stole-${id}`);
                if (row) row.remove();
            } else {
                if (takenStoles.includes(String(id))) return;
                selectedStoles.add(id);
                addRow(id);
            }
            calculateTotal();
            window.dispatchEvent(new CustomEvent('refresh-stoles'));
        }

        function addRow(stoleId) {
            const container = document.getElementById('itemsContainer');
            const row = document.createElement('tr');
            row.id = `row-stole-${stoleId}`;
            row.className = 'item-row';
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    <input type="hidden" name="items[${rowIndex}][place]" value="${stoleId}">
                    Stole ${stoleId}
                </td>
                {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <input type="number" name="items[${rowIndex}][quantity]" value="1" min="1" required class="w-20 rounded-md border-gray-300" onchange="calculateTotal()">
                </td> --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <input type="number" name="items[${rowIndex}][price]" value="0" min="0" step="0.01" required class="w-32 rounded-md border-gray-300" onchange="calculateTotal()">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button type="button" onclick="toggleStole(${stoleId})" class="text-red-600 hover:text-red-900">Remove</button>
                </td>
            `;
            container.appendChild(row);
            rowIndex++;
        }

        function calculateTotal() {
            const rows = document.querySelectorAll('.item-row');
            let total = 0;
            rows.forEach(row => {
                // const qty = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
                const price = parseFloat(row.querySelector('input[name*="[price]"]').value) || 0;
                const subtotal = price; // qty assumed 1
                // row.querySelector('.subtotal').textContent = subtotal.toFixed(2); // Removed
                total += subtotal;
            });
            document.getElementById('totalAmount').textContent = total.toFixed(2);
        }
    </script>
</x-app-layout>
