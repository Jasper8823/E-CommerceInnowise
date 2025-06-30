<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\LogInController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/products');
});

Route::resources([
    'admin/products' => AdminController::class,
    'products' => GuestController::class,
]);

Route::controller(RegistrationController::class)->group(function () {
    Route::get('/auth', 'create');
    Route::post('/auth', 'store');
});
Route::controller(LogInController::class)->group(function () {
    Route::get('/login', 'auth');
    Route::post('/login', 'check');
    Route::get('/logout', 'logout');
});
