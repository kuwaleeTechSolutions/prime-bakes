<?php

use App\Models\CashRegister;
use App\Models\Sale;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/pos', fn () => view('pos.index'))->name('pos.index');

    Route::get('/cash-register/{cashRegister}/close', fn (CashRegister $cashRegister) => view('pos.close-register', compact('cashRegister')))
        ->name('cash-register.close');

    Route::get('/sales', fn () => view('sales.index'))->name('sales.index');
    Route::get('/sales/{sale}', fn (Sale $sale) => view('sales.show', compact('sale')))->name('sales.show');
    Route::get('/sales/{sale}/receipt', fn (Sale $sale) => view('sales.receipt', [
    'sale' => $sale->load(['lines.product', 'payments', 'warehouse', 'customer', 'user']),
        ]))->name('sales.receipt');
});
