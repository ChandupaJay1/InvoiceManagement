<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::where('user_id', Auth::id())
            ->with('items')
            ->latest('invoice_date')
            ->latest('id')
            ->paginate(20);

        return view('invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        return view('invoices.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_contact' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.place' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Calculate total
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['quantity'] * $item['price'];
            }

            // Create invoice
            $invoice = Invoice::create([
                'user_id' => Auth::id(),
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'customer_name' => $validated['customer_name'],
                'customer_contact' => $validated['customer_contact'],
                'invoice_date' => $validated['invoice_date'],
                'total' => $total,
            ]);

            // Create invoice items
            foreach ($validated['items'] as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'place' => $item['place'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('invoices.show', $invoice)
                ->with('success', 'Invoice created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to create invoice. Please try again.');
        }
    }

    public function show(Invoice $invoice): View
    {
        // Check if user owns this invoice
        if ($invoice->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to invoice.');
        }

        $invoice->load('items');

        return view('invoices.show', compact('invoice'));
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice deleted successfully!');
    }

}
