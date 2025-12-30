<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): \Illuminate\Http\RedirectResponse|\Illuminate\View\View
    {
        $user = Auth::user();

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // Daily sales - today's invoices
        $dailySales = Invoice::where('user_id', $user->id)
            ->whereDate('invoice_date', today())
            ->sum('total');

        // Monthly sales - current month
        $monthlySales = Invoice::where('user_id', $user->id)
            ->whereMonth('invoice_date', now()->month)
            ->whereYear('invoice_date', now()->year)
            ->sum('total');

        // Total invoices count
        $totalInvoices = Invoice::where('user_id', $user->id)->count();

        // Recent invoices (last 5)
        $recentInvoices = Invoice::where('user_id', $user->id)
            ->with('items')
            ->latest('invoice_date')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'dailySales',
            'monthlySales',
            'totalInvoices',
            'recentInvoices'
        ));
    }
}
