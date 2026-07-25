<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Matches the sidebar's existing 'reports.index' link from the theme package.
    Route::get('/reports', fn () => view('reports.index'))->name('reports.index');

    Route::get('/reports/sales', fn () => view('reports.sales'))->name('reports.sales');
    Route::get('/reports/purchases', fn () => view('reports.purchases'))->name('reports.purchases');
    Route::get('/reports/profit-loss', fn () => view('reports.profit-loss'))->name('reports.profit-loss');
    Route::get('/reports/stock', fn () => view('reports.stock'))->name('reports.stock');
    Route::get('/reports/due', fn () => view('reports.due'))->name('reports.due');
    Route::get('/reports/expenses', fn () => view('reports.expenses'))->name('reports.expenses');
    Route::get('/reports/tax', fn () => view('reports.tax'))->name('reports.tax');
    Route::get('/reports/register', fn () => view('reports.register'))->name('reports.register');
});
