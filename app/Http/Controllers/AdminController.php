<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalInvoices = Invoice::count();
        $totalStoles = \App\Models\InvoiceItem::whereHas('invoice', function($query) {
            $query->whereDate('invoice_date', today());
        })->count();
        $totalCapacity = 600;
        $totalCollection = Invoice::sum('total');
        
        // Get stoles taken TODAY only (for daily reset)
        $takenStoles = \App\Models\InvoiceItem::whereHas('invoice', function($query) {
            $query->whereDate('invoice_date', today());
        })->pluck('place')->map(function($place) {
            return (int) $place;
        })->unique()->sort()->values()->toArray();

        return view('admin.dashboard', compact('totalUsers', 'totalInvoices', 'totalStoles', 'totalCapacity', 'totalCollection', 'takenStoles'));
    }

    public function collections()
    {
        $users = User::where('is_admin', false)
            ->withCount('invoices')
            ->get()
            ->map(function($user) {
                $user->today_total = Invoice::where('user_id', $user->id)
                    ->whereDate('invoice_date', today())
                    ->sum('total');
                $user->month_total = Invoice::where('user_id', $user->id)
                    ->whereMonth('invoice_date', now()->month)
                    ->whereYear('invoice_date', now()->year)
                    ->sum('total');
                $user->lifetime_total = Invoice::where('user_id', $user->id)
                    ->sum('total');
                return $user;
            });

        return view('admin.collections.index', compact('users'));
    }

    public function users()
    {
        $users = User::latest()
            ->withCount('invoices')
            ->withSum('invoices', 'total')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'required|boolean',
            'is_active' => 'required|boolean',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'is_admin' => $validated['is_admin'],
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'required|boolean',
            'is_active' => 'required|boolean',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->is_admin = $validated['is_admin'];
        $user->is_active = $validated['is_active'];
        
        if ($request->filled('password')) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot disable your own account.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'enabled' : 'disabled';
        return back()->with('success', "User account has been $status.");
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        // Optional: delete or reassign user's invoices
        $user->invoices()->delete();
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    public function userInvoices(User $user)
    {
        // Daily sales
        $dailySales = Invoice::where('user_id', $user->id)
            ->whereDate('invoice_date', today())
            ->sum('total');

        // Monthly sales
        $monthlySales = Invoice::where('user_id', $user->id)
            ->whereMonth('invoice_date', now()->month)
            ->whereYear('invoice_date', now()->year)
            ->sum('total');

        // Get all invoices ordered by date for grouping
        $invoices = $user->invoices()
            ->latest('invoice_date')
            ->get()
            ->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->invoice_date)->format('F Y'); // Group by Month Year
            });

        return view('admin.users.show', compact('user', 'invoices', 'dailySales', 'monthlySales'));
    }

    public function backup(Request $request)
    {
        $userId = $request->query('user_id');
        $filename = $userId 
            ? 'invoices_backup_user_' . $userId . '.csv' 
            : 'invoices_backup_full.csv';

        $query = Invoice::with(['user', 'items']);
        if ($userId) {
            $query->where('user_id', $userId);
        }
        $invoices = $query->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($invoices) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Invoice ID', 'Invoice Number', 'User Email', 'Customer Name', 
                'Customer Contact', 'Date', 'Total', 'Item Place', 
                'Item Quantity', 'Item Unit Price', 'Item Subtotal'
            ]);

            foreach ($invoices as $invoice) {
                foreach ($invoice->items as $item) {
                    fputcsv($file, [
                        $invoice->id,
                        $invoice->invoice_number,
                        $invoice->user->email,
                        $invoice->customer_name,
                        $invoice->customer_contact,
                        $invoice->invoice_date->format('Y-m-d'),
                        $invoice->total,
                        $item->place,
                        $item->quantity,
                        $item->unit_price,
                        $item->subtotal
                    ]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function restore(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:csv,txt,xlsx,xls', // allowing excel mime types just in case user tries, but logic is CSV
        ]);

        try {
            $file = $request->file('backup_file');
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));
            $header = array_shift($data); // Remove header

            // Group by invoice number (index 1)
            $groupedInvoices = [];
            foreach ($data as $row) {
                if (count($row) < 11) continue; // Skip invalid rows
                $groupedInvoices[$row[1]][] = $row;
            }

            foreach ($groupedInvoices as $invoiceNumber => $items) {
                $firstRow = $items[0];

                // Find or Create User
                $user = User::firstOrCreate(
                    ['email' => $firstRow[2]],
                    ['name' => 'Imported User', 'password' => bcrypt('password')]
                );

                // Find or Create Invoice
                $invoice = Invoice::firstOrCreate(
                    ['invoice_number' => $invoiceNumber],
                    [
                        'user_id' => $user->id,
                        'customer_name' => $firstRow[3],
                        'customer_contact' => $firstRow[4],
                        'invoice_date' => $firstRow[5],
                        'total' => $firstRow[6],
                    ]
                );

                // Re-sync items
                $invoice->items()->delete();

                foreach ($items as $item) {
                    \App\Models\InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'place' => $item[7],
                        'quantity' => $item[8],
                        'unit_price' => $item[9],
                        'subtotal' => $item[10],
                    ]);
                }
                
                // Recalculate total to be safe
                $invoice->calculateTotal();
            }

            return back()->with('success', 'Data restored successfully from CSV!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error restoring data: ' . $e->getMessage());
        }
    }

    public function allInvoices(Request $request)
    {
        $query = Invoice::with('user');

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('invoice_date', $request->date);
        }

        $invoices = $query->latest()->paginate(20);
        $users = User::where('is_admin', false)->orderBy('name')->get();

        return view('admin.invoices.index', compact('invoices', 'users'));
    }

    public function monthlyReport()
    {
        // Group by Month (Y-m), then by User
        // Filter out admin users from the report
        $report = Invoice::selectRaw('DATE_FORMAT(invoice_date, "%Y-%m") as month_year, user_id, SUM(total) as total_amount, COUNT(id) as invoice_count')
            ->whereHas('user', function($query) {
                $query->where('is_admin', false);
            })
            ->with('user')
            ->groupBy('month_year', 'user_id')
            ->orderBy('month_year', 'desc')
            ->get()
            ->groupBy('month_year');

        return view('admin.reports.monthly', compact('report'));
    }
}
