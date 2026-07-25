<?php

use App\Models\Customer;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Matches the sidebar's existing 'people.index' link from the theme package.
    Route::get('/people', fn () => view('people.index'))->name('people.index');

    Route::get('/customers', fn () => view('customers.index'))->name('customers.index');
    Route::get('/customers/create', fn () => view('customers.create'))->name('customers.create');
    Route::get('/customers/{customer}/edit', fn (Customer $customer) => view('customers.edit', compact('customer')))->name('customers.edit');

    Route::get('/customer-groups', fn () => view('customer-groups.index'))->name('customer-groups.index');
    Route::get('/billers', fn () => view('billers.index'))->name('billers.index');
});
