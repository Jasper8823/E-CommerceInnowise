<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminManufacturerController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminProductTypeController;
use App\Http\Controllers\Guest\GuestProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect('/admin/products')
        : redirect('/products');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/admin/products/export', [AdminProductController::class, 'export'])->name('admin.products.export');
    Route::resource('admin/products', AdminProductController::class)->names('admin.products');
    Route::resource('admin/product_types', AdminProductTypeController::class)->names('admin.product_types');
    Route::resource('admin/manufacturers', AdminManufacturerController::class)->names('admin.manufacturers');
});

Route::resources([
    'products' => GuestProductController::class,
]);

require __DIR__.'/auth.php';
