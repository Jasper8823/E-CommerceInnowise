<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CurrencyRate;
use App\Models\Product;
use App\Models\ProductType;
use App\Services\ProductQueryService;
use Illuminate\Http\Request;

final class GuestProductController extends Controller
{
    protected ProductQueryService $productQueryService;

    public function __construct(ProductQueryService $productQueryService)
    {
        $this->productQueryService = $productQueryService;
    }

    public function index(Request $request)
    {
        $query = $this->productQueryService->getBuilder($request);
        $types = ProductType::all();
        $products = $query->paginate(30);
        $ratesDB = CurrencyRate::all();
        $rates = [];

        foreach ($ratesDB as $rate) {
            $rates[$rate->currency] = $rate->rate;
        }

        if ($request->filled('currency-selector')) {
            $rate = $request->input('currency-selector');
        }else{
            $rate = 'USD';
        }

        return view('product.guest.products', ['products' => $products, 'types' => $types, 'rates' => $rates, 'rate' => $rate]);
    }

    public function show($id, Request $request)
    {
        $product = Product::all()->where('uuId', $id)->first();
        $ratesDB = CurrencyRate::all();
        $rates = [];

        foreach ($ratesDB as $rate) {
            $rates[$rate->currency] = $rate->rate;
        }

        if ($request->filled('currency-selector')) {
            $rate = $request->input('currency-selector');
        }else{
            $rate = 'USD';
        }

        return view('product.guest.show', ['product' => $product, 'rates' => $rates, 'rate' => $rate]);
    }
}
