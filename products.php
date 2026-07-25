<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Products module routes
|--------------------------------------------------------------------------
| Merge into routes/web.php inside your existing auth-protected group.
| Uses route-model binding for /products/{product}/edit.
*/

Route::middleware('auth')->group(function () {
    Route::get('/products', fn () => view('products.index'))->name('products.index');
    Route::get('/products/create', fn () => view('products.create'))->name('products.create');
    Route::get('/products/{product}/edit', fn (Product $product) => view('products.edit', compact('product')))->name('products.edit');

    Route::get('/categories', fn () => view('categories.index'))->name('categories.index');
    Route::get('/brands', fn () => view('brands.index'))->name('brands.index');
    Route::get('/units', fn () => view('units.index'))->name('units.index');
});
