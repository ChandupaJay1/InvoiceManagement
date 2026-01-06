<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::get('/invoices/stoles/taken', [InvoiceController::class, 'getTakenStoles'])->name('invoices.stoles.taken');
    Route::get('/invoices/{invoice}/print-raw', [InvoiceController::class, 'printRaw'])->name('invoices.print_raw');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    // Admin Routes
    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
        Route::get('/collections', [\App\Http\Controllers\AdminController::class, 'collections'])->name('collections');
        
        // User Management
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users.index');
        Route::get('/users/create', [\App\Http\Controllers\AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
        Route::patch('/users/{user}/toggle-status', [\App\Http\Controllers\AdminController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.destroy');
        Route::get('/users/{user}', [\App\Http\Controllers\AdminController::class, 'userInvoices'])->name('users.show');

        // Reports & Misc
        Route::get('/reports/monthly', [\App\Http\Controllers\AdminController::class, 'monthlyReport'])->name('reports.monthly');
        Route::get('/invoices', [\App\Http\Controllers\AdminController::class, 'allInvoices'])->name('invoices.index');
        Route::get('/backup', [\App\Http\Controllers\AdminController::class, 'backup'])->name('backup');
        Route::post('/restore', [\App\Http\Controllers\AdminController::class, 'restore'])->name('restore');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
