<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Create New Invoice</h2>
            <p class="text-gray-600">Fill in the details below to create a new invoice</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
                @csrf

                <!-- Customer Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Customer Name *</label>
                            <input type="text"
                                   name="customer_name"
                                   id="customer_name"
                                   value="{{ old('customer_name') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('customer_name') border-red-500 @enderror">
                            @error('customer_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_contact" class="block text-sm font-medium text-gray-700 mb-1">Customer Contact *</label>
                            <input type="text"
                                   name="customer_contact"
                                   id="customer_contact"
                                   value="{{ old('customer_contact') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('customer_contact') border-red-500 @enderror">
                            @error('customer_contact')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="invoice_date" class="block text-sm font-medium text-gray-700 mb-1">Invoice Date *</label>
                            <input type="date"
                                   name="invoice_date"
                                   id="invoice_date"
                                   value="{{ old('invoice_date', date('Y-m-d')) }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('invoice_date') border-red-500 @enderror">
                            @error('invoice_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Invoice Items -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Invoice Place</h3>
                        <button type="button"
                                onclick="addInvoiceItem()"
                                class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                            + Add Item
                        </button>
                    </div>

                    <div id="itemsContainer">
                        <div class="item-row grid grid-cols-12 gap-3 mb-3">
                            <div class="col-span-12 md:col-span-5">
                                <input type="text"
                                       name="items[0][place]"
                                       placeholder="Place *"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div class="col-span-6 md:col-span-2">
                                <input type="number"
                                       name="items[0][quantity]"
                                       placeholder="Qty *"
                                       min="1"
                                       value="1"
                                       required
                                       onchange="calculateTotal()"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div class="col-span-6 md:col-span-3">
                                <input type="number"
                                       name="items[0][price]"
                                       placeholder="Price *"
                                       min="0"
                                       step="0.01"
                                       value="0"
                                       required
                                       onchange="calculateTotal()"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div class="col-span-12 md:col-span-2 flex items-center justify-end">
                                <span class="text-sm font-medium text-gray-600">Subtotal: $<span class="subtotal">0.00</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="border-t border-gray-200 pt-6 mb-6">
                    <div class="flex justify-end">
                        <div class="w-full md:w-64">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="text-gray-800 font-medium">$<span id="subtotalAmount">0.00</span></span>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                <span class="text-xl font-bold text-gray-800">Total:</span>
                                <span class="text-2xl font-bold text-indigo-600">$<span id="totalAmount">0.00</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('dashboard') }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium text-center">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        Create Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let itemCount = 1;

        function addInvoiceItem() {
            const container = document.getElementById('itemsContainer');
            const newRow = document.createElement('div');
            newRow.className = 'item-row grid grid-cols-12 gap-3 mb-3';
            newRow.innerHTML = `
                <div class="col-span-12 md:col-span-5">
                    <input type="text"
                           name="items[${itemCount}][place]"
                           placeholder="Place *"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div class="col-span-6 md:col-span-2">
                    <input type="number"
                           name="items[${itemCount}][quantity]"
                           placeholder="Qty *"
                           min="1"
                           value="1"
                           required
                           onchange="calculateTotal()"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div class="col-span-6 md:col-span-3">
                    <input type="number"
                           name="items[${itemCount}][price]"
                           placeholder="Price *"
                           min="0"
                           step="0.01"
                           value="0"
                           required
                           onchange="calculateTotal()"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div class="col-span-10 md:col-span-2 flex items-center">
                    <span class="text-sm font-medium text-gray-600">$<span class="subtotal">0.00</span></span>
                </div>
                <div class="col-span-2 md:col-span-12 md:hidden flex items-center justify-end">
                    <button type="button"
                            onclick="removeInvoiceItem(this)"
                            class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition text-xl font-bold">
                        ×
                    </button>
                </div>
                <div class="hidden md:flex md:col-span-12 justify-end mt-2">
                    <button type="button"
                            onclick="removeInvoiceItem(this)"
                            class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition text-sm font-medium">
                        Remove Item
                    </button>
                </div>
            `;
            container.appendChild(newRow);
            itemCount++;
            calculateTotal();
        }

        function removeInvoiceItem(button) {
            const row = button.closest('.item-row');
            row.remove();
            calculateTotal();
        }

        function calculateTotal() {
            const rows = document.querySelectorAll('.item-row');
            let total = 0;

            rows.forEach(row => {
                const qty = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
                const price = parseFloat(row.querySelector('input[name*="[price]"]').value) || 0;
                const subtotal = qty * price;

                row.querySelector('.subtotal').textContent = subtotal.toFixed(2);
                total += subtotal;
            });

            document.getElementById('subtotalAmount').textContent = total.toFixed(2);
            document.getElementById('totalAmount').textContent = total.toFixed(2);
        }

        // Calculate total on page load
        document.addEventListener('DOMContentLoaded', function() {
            calculateTotal();
        });
    </script>
</x-app-layout>
