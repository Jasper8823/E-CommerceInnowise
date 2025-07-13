<?php

declare(strict_types=1);

use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\GuestProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect('/admin/products')
        : redirect('/products');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/admin/products/export', [AdminProductController::class, 'export'])->name('admin.products.export');
    Route::resource('admin/products', AdminProductController::class)->names('admin.products');
});

Route::resources([
    'products' => GuestProductController::class,
]);

require __DIR__.'/auth.php';
