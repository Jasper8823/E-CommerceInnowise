<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProductFilterRequest;
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

    public function index(ProductFilterRequest $request)
    {
        $query = $this->productQueryService->getBuilder($request);
        $types = ProductType::all();
        $products = $query->paginate(30);
        $ratesDB = CurrencyRate::all();
        $rates = [];

        foreach ($ratesDB as $rate) {
            $rates = CurrencyRate::pluck('rate', 'currency');
        }

        $allowedRates = ['USD', 'EUR', 'PLN'];

        $rate = in_array($request->input('currency-selector'), $allowedRates)
            ? $request->input('currency-selector')
            : 'USD';

        return view('product.guest.index', ['products' => $products, 'types' => $types, 'rates' => $rates, 'rate' => $rate]);
    }

    public function show($id, Request $request)
    {
        $product = Product::all()->where('uuid', $id)->first();
        $ratesDB = CurrencyRate::all();
        $rates = [];

        foreach ($ratesDB as $rate) {
            $rates = CurrencyRate::pluck('rate', 'currency');
        }

        $allowedRates = ['USD', 'EUR', 'PLN'];

        $rate = in_array($request->input('currency-selector'), $allowedRates)
            ? $request->input('currency-selector')
            : 'USD';

        return view('product.guest.show', ['product' => $product, 'rates' => $rates, 'rate' => $rate]);
    }
}
