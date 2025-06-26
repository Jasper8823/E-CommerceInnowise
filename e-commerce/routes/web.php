<?php

use App\Http\Controllers\LogInController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/products');
});

Route::controller(ProductController::class)->group(function (){
    Route::get('/products', 'index');
    Route::get('/admin/products', 'adminIndex');
    Route::get('/products/create', 'create');
    Route::get('/products/{id}', 'show');
});

Route::controller(RegistrationController::class)->group(function () {
    Route::get('/auth', 'create');
    Route::post('/auth', 'store');
});
Route::controller(LogInController::class)->group(function () {
    Route::get('/login', 'auth');
    Route::post('/login', 'check');
    Route::get('/logout', 'logout');
});
