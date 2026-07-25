<?php

use App\Models\Purchase;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/purchases', fn () => view('purchases.index'))->name('purchases.index');
    Route::get('/purchases/create', fn () => view('purchases.create'))->name('purchases.create');
    Route::get('/purchases/{purchase}/edit', fn (Purchase $purchase) => view('purchases.edit', compact('purchase')))->name('purchases.edit');

    Route::get('/suppliers', fn () => view('suppliers.index'))->name('suppliers.index');
});
