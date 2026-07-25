<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/stock', fn () => view('stock.index'))->name('stock.index');
    Route::get('/transfers', fn () => view('transfers.index'))->name('transfers.index');
    Route::get('/transfers/create', fn () => view('transfers.create'))->name('transfers.create');

    Route::get('/adjustments', fn () => view('adjustments.index'))->name('adjustments.index');
    Route::get('/adjustments/create', fn () => view('adjustments.create'))->name('adjustments.create');

    Route::get('/damages', fn () => view('damages.index'))->name('damages.index');
    Route::get('/damages/create', fn () => view('damages.create'))->name('damages.create');

    Route::get('/stock-counts', fn () => view('stock-counts.index'))->name('stock-counts.index');
    Route::get('/stock-counts/create', fn () => view('stock-counts.create'))->name('stock-counts.create');
});
