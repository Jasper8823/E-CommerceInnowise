<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return redirect('/products');
});

Route::get('/products', function () {
    $products = Product::all();

    return view('products', ['products'=>$products]);
});

Route::get('/products/{id}', function ($id){
    $product =  Product::all()->where('id',$id)->first();
    return view('show', ['product' => $product]);
});
