<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Every module keeps its own routes file (routes/products.php, routes/sales.php,
| etc.) for readability — this file just wires them all together plus the
| one route no module owns: the dashboard itself.
*/

Route::get('/', function () { return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login'); });

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
});

require __DIR__.'/auth.php';
require __DIR__.'/products.php';
require __DIR__.'/purchases.php';
require __DIR__.'/stock.php';
require __DIR__.'/people.php';
require __DIR__.'/sales.php';
require __DIR__.'/accounting.php';
require __DIR__.'/hrm.php';
require __DIR__.'/settings.php';
require __DIR__.'/reports.php';
