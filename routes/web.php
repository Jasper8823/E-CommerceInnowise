<?php

use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\GuestProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect('/admin/products')
        : redirect('/products');
});

Route::middleware(['auth'])->group(function () {
    Route::resources([
        'admin/products' => AdminProductController::class
    ]);
});

Route::resources([
    'products' => GuestProductController::class,
]);

require __DIR__.'/auth.php';
