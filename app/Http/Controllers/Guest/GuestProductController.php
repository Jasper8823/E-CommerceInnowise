<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guest;

use App\Enums\Currency;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFilterRequest;
use App\Models\CurrencyRate;
use App\Models\Product;
use App\Models\ProductType;
use App\Services\ProductQueryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GuestProductController extends Controller
{
    public function __construct(
        protected ProductQueryService $productQueryService,
    ) {}

    public function index(ProductFilterRequest $request): View
    {
        $type = $request->input('type');
        $name = $request->input('name');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sort = $request->input('sort');

        $products = $this->productQueryService->getBuilder($type, $name, $minPrice, $maxPrice, $sort);
        $types = ProductType::all();

        $rates = CurrencyRate::pluck('rate', 'currency');

        $allowedRates = Currency::names();

        $rate = in_array($request->input('currency-selector'), $allowedRates)
            ? $request->input('currency-selector')
            : 'USD';

        return view('product.guest.index', ['products' => $products, 'types' => $types, 'rates' => $rates, 'rate' => $rate]);
    }

    public function show($id, Request $request): View
    {
        $product = Product::all()->where('uuid', $id)->first();
        $rates = CurrencyRate::pluck('rate', 'currency');

        $allowedRates = Currency::names();

        $rate = in_array($request->input('currency-selector'), $allowedRates)
            ? $request->input('currency-selector')
            : 'USD';

        return view('product.guest.show', ['product' => $product, 'rates' => $rates, 'rate' => $rate]);
    }
}
