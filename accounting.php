<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Matches the sidebar's existing 'accounting.index' link from the theme package.
    Route::get('/accounting', fn () => view('accounting.index'))->name('accounting.index');

    Route::get('/accounts', fn () => view('accounts.index'))->name('accounts.index');
    Route::get('/expense-categories', fn () => view('expense-categories.index'))->name('expense-categories.index');

    Route::get('/expenses', fn () => view('expenses.index'))->name('expenses.index');
    Route::get('/expenses/create', fn () => view('expenses.create'))->name('expenses.create');

    Route::get('/money-transfers', fn () => view('money-transfers.index'))->name('money-transfers.index');
    Route::get('/money-transfers/create', fn () => view('money-transfers.create'))->name('money-transfers.create');

    Route::get('/deposits', fn () => view('deposits.index'))->name('deposits.index');
    Route::get('/gift-cards', fn () => view('gift-cards.index'))->name('gift-cards.index');
});
