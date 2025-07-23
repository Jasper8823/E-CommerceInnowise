<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guest;

use App\Enums\Currency;
use App\Http\Controllers\Controller;
use App\Http\Requests\GuestShowRequest;
use App\Http\Requests\ProductFilterRequest;
use App\Models\CurrencyRate;
use App\Models\Product;
use App\Models\ProductType;
use App\Services\ProductQueryService;
use Illuminate\View\View;

use function PHPUnit\Framework\isEmpty;

final class GuestProductController extends Controller
{
    public function __construct(
        protected ProductQueryService $productQueryService,
    ) {}

    public function index(ProductFilterRequest $request): View
    {
        $validated = $request->validated();

        $type = $validated['type'] ?? null;
        $name = $validated['name'] ?? null;
        $minPrice = $validated['min_price'] ?? null;
        $maxPrice = $validated['max_price'] ?? null;
        $sort = $validated['sort'] ?? null;

        $products = $this->productQueryService->getBuilder($type, $name, $minPrice, $maxPrice, $sort);
        $types = ProductType::all();

        $rates = CurrencyRate::pluck('rate', 'currency');

        $allowedRates = Currency::names();

        $currency = $validated['currency-selector'] ?? null;

        $rate = isEmpty($currency) && in_array($currency, $allowedRates)
            ? $validated['currency-selector']
            : 'USD';

        return view('product.guest.index', ['products' => $products, 'types' => $types, 'rates' => $rates, 'rate' => $rate]);
    }

    public function show($id, GuestShowRequest $request): View
    {
        $validated = $request->validated();

        $product = Product::where('uuid', $id)->first();
        $rates = CurrencyRate::pluck('rate', 'currency');

        $allowedRates = Currency::names();
        $currency = $validated['currency-selector'] ?? null;

        $rate = isEmpty($currency) && in_array($currency, $allowedRates)
            ? $currency
            : 'USD';

        return view('product.guest.show', ['product' => $product, 'rates' => $rates, 'rate' => $rate]);
    }
}
