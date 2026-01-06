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

use Illuminate\Validation\Rule;

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

    public function create(Request $request): View
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $takenStoles = InvoiceItem::whereHas('invoice', function($query) use ($date) {
            $query->whereDate('invoice_date', $date);
        })->pluck('place')->toArray();

        return view('invoices.create', compact('takenStoles', 'date'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_contact' => 'nullable|string|max:255',
            'invoice_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.place' => [
                'required',
                'string',
                'max:255',
                'distinct',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = InvoiceItem::where('place', $value)
                        ->whereHas('invoice', function ($query) use ($request) {
                            $query->whereDate('invoice_date', $request->invoice_date);
                        })->exists();

                    if ($exists) {
                        $fail("The stole {$value} is already taken for this date.");
                    }
                },
            ],
            // 'items.*.quantity' => 'required|integer|min:1', // Removed
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Calculate total
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['price']; // Quantity is assumed 1
            }

            // Collect all stole numbers
            $stoleNumbers = collect($validated['items'])->pluck('place')->sort()->implode(', ');

            // Create invoice
            $invoice = Invoice::create([
                'user_id' => Auth::id(),
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'customer_name' => $validated['customer_name'] ?? 'Guest',
                'customer_contact' => $validated['customer_contact'] ?? '',
                'invoice_date' => $validated['invoice_date'],
                'stoles' => $stoleNumbers,
                'total' => $total,
            ]);

            // Create invoice items
            foreach ($validated['items'] as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'place' => $item['place'],
                    // 'quantity' => $item['quantity'], // Removed
                    'price' => $item['price'],
                    'subtotal' => $item['price'], // Quantity is 1
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

    public function edit(Invoice $invoice): View
    {
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        $invoice->load('items');
        $date = $invoice->invoice_date->format('Y-m-d');

        $takenStoles = InvoiceItem::whereHas('invoice', function($query) use ($date) {
            $query->whereDate('invoice_date', $date);
        })->where('invoice_id', '!=', $invoice->id)
          ->pluck('place')->toArray();

        return view('invoices.edit', compact('invoice', 'takenStoles', 'date'));
    }

    public function getTakenStoles(Request $request)
    {
        $date = $request->get('date');
        $excludeId = $request->get('exclude_invoice_id');

        $query = InvoiceItem::whereHas('invoice', function($q) use ($date) {
            $q->whereDate('invoice_date', $date);
        });

        if ($excludeId) {
            $query->where('invoice_id', '!=', $excludeId);
        }

        return response()->json($query->pluck('place')->toArray());
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_contact' => 'nullable|string|max:255',
            'invoice_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.place' => [
                'required',
                'string',
                'max:255',
                'distinct',
                function ($attribute, $value, $fail) use ($request, $invoice) {
                    $exists = InvoiceItem::where('place', $value)
                        ->where('invoice_id', '!=', $invoice->id)
                        ->whereHas('invoice', function ($query) use ($request) {
                            $query->whereDate('invoice_date', $request->invoice_date);
                        })->exists();

                    if ($exists) {
                        $fail("The stole {$value} is already taken for this date.");
                    }
                },
            ],
            // 'items.*.quantity' => 'required|integer|min:1', // Removed
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Calculate total
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['price']; // Quantity is assumed 1
            }

            // Collect all stole numbers
            $stoleNumbers = collect($validated['items'])->pluck('place')->sort()->implode(', ');

            // Update invoice
            $invoice->update([
                'customer_name' => $validated['customer_name'] ?? 'Guest',
                'customer_contact' => $validated['customer_contact'] ?? '',
                'invoice_date' => $validated['invoice_date'],
                'stoles' => $stoleNumbers,
                'total' => $total,
            ]);

            // Sync items (delete all and recreate is simplest safe approach for now)
            // A more optimized approach would be diffing, but we want simplicity and correctness.
            $invoice->items()->delete();

            foreach ($validated['items'] as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'place' => $item['place'],
                    // 'quantity' => $item['quantity'], // Removed
                    'price' => $item['price'],
                    'subtotal' => $item['price'], // Quantity is 1
                ]);
            }

            DB::commit();

            return redirect()
                ->route('invoices.show', $invoice)
                ->with('success', 'Invoice updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to update invoice. Please try again.');
        }
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


    public function printRaw(Invoice $invoice)
    {
        // JSON Format for Mate Bluetooth Print App
        // [
        //   {"content": "Text", "align": 0-2, "bold": 0-1, "format": 0-3},
        //   ...
        // ]
        // Align: 0=Left, 1=Center, 2=Right
        // Bold: 0=No, 1=Yes
        // Format: 0=Normal, 1=Double Height, 2=Double Height+Width, 3=Double Width
        
        $data = [];
        
        // Header
        $data[] = ['content' => "ALL WEEKLY FAIR - KIRINDIWELA\n", 'align' => 1, 'bold' => 1, 'format' => 1];
        $data[] = ['content' => "(Tenderer Wimal Fish Stall)\n\n", 'align' => 1, 'bold' => 0, 'format' => 0];
        
        // Invoice Info
        $data[] = ['content' => "INV NO: " . $invoice->invoice_number . "\n", 'align' => 0, 'bold' => 0, 'format' => 0];
        $data[] = ['content' => "DATE: " . $invoice->created_at->format('d/m/Y h:i A') . "\n", 'align' => 0, 'bold' => 0, 'format' => 0];
        $data[] = ['content' => "ISSUED BY: " . $invoice->user->name . "\n", 'align' => 0, 'bold' => 0, 'format' => 0];
        $data[] = ['content' => "CUSTOMER: " . $invoice->customer_name . "\n", 'align' => 0, 'bold' => 0, 'format' => 0];
        
        $data[] = ['content' => "--------------------------------\n", 'align' => 1, 'bold' => 1, 'format' => 0];
        
        // Items Header
        $data[] = ['content' => "ITEM                       PRICE\n", 'align' => 0, 'bold' => 1, 'format' => 0];
        $data[] = ['content' => "--------------------------------\n", 'align' => 1, 'bold' => 1, 'format' => 0];
        
        // Items
        foreach ($invoice->items as $item) {
            $name = "Stole " . $item->place;
            $price = number_format($item->price, 2);
            
            // Manual padding for alignment since we can't fully control tab stops in simple JSON lines
            // Assuming simplified view where each content object is a line or block
            $padding = 32 - strlen($name) - strlen($price);
            if ($padding < 1) $padding = 1;
            $spaces = str_repeat(" ", $padding);
            
            $data[] = ['content' => $name . $spaces . $price . "\n", 'align' => 0, 'bold' => 0, 'format' => 0];
        }
        
        $data[] = ['content' => "--------------------------------\n", 'align' => 1, 'bold' => 1, 'format' => 0];
        
        // Total
        $totalLine = "TOTAL: Rs." . number_format($invoice->total, 2) . "\n\n";
        $data[] = ['content' => $totalLine, 'align' => 2, 'bold' => 1, 'format' => 0]; // Double width might break alignment with fixed font so stick to normal/bold
        
        // Footer
        $data[] = ['content' => "Thank You!\n", 'align' => 1, 'bold' => 1, 'format' => 0];
        $data[] = ['content' => "Software By Tharadi Piyasa\n", 'align' => 1, 'bold' => 0, 'format' => 0];
        $data[] = ['content' => "071*******\n\n\n", 'align' => 1, 'bold' => 0, 'format' => 0]; 
        
        return response()->json($data);
    }
}
