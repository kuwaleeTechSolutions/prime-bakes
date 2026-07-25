<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Matches the sidebar's existing 'settings.index' link from the theme package.
    Route::get('/settings', fn () => view('settings.index'))->name('settings.index');
    Route::get('/settings/general', fn () => view('settings.general'))->name('settings.general');
    Route::get('/settings/pos', fn () => view('settings.pos'))->name('settings.pos');

    Route::get('/warehouses', fn () => view('warehouses.index'))->name('warehouses.index');
    Route::get('/taxes', fn () => view('taxes.index'))->name('taxes.index');
    Route::get('/currencies', fn () => view('currencies.index'))->name('currencies.index');
    Route::get('/roles', fn () => view('roles.index'))->name('roles.index');

    Route::get('/users', fn () => view('users.index'))->name('users.index');
    Route::get('/users/create', fn () => view('users.create'))->name('users.create');
    Route::get('/users/{user}/edit', fn (User $user) => view('users.edit', compact('user')))->name('users.edit');
});
