<?php

use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
| Merge into your routes/web.php. Login/Register pages render Livewire
| components directly, so there's no separate POST route for them — the
| Livewire component's login()/register() method handles submission.
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');
    Route::get('/register', fn () => view('auth.register'))->name('register');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', LogoutController::class)->name('logout');
});
