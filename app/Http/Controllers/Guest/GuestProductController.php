<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guest;

use App\Enums\Currency;
use App\Http\Controllers\Controller;
use App\Http\Requests\GuestShowRequest;
use App\Http\Requests\ProductFilterRequest;
use App\Repositories\Contracts\CurrencyRateRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\ProductTypeRepositoryInterface;
use App\Services\ProductQueryService;
use Illuminate\View\View;

final class GuestProductController extends Controller
{
    public function __construct(
        protected ProductQueryService $productQueryService,
        protected ProductRepositoryInterface $productRepository,
        protected ProductTypeRepositoryInterface $productTypeRepository,
        protected CurrencyRateRepositoryInterface $currencyRateRepository,
    ) {}

    public function index(ProductFilterRequest $request): View
    {
        $validated = $request->validated();

        $products = $this->productQueryService->getBuilder(
            $validated['type'] ?? null,
            $validated['name'] ?? null,
            $validated['min_price'] ?? null,
            $validated['max_price'] ?? null,
            $validated['sort'] ?? null
        );

        $types = $this->productTypeRepository->all();
        $rates = $this->currencyRateRepository->pluck();
        $allowedRates = Currency::names();

        $currency = $validated['currency-selector'] ?? null;
        $rate = in_array($currency, $allowedRates, true)
            ? $currency
            : 'USD';

        return view('product.guest.index', [
            'products' => $products,
            'types' => $types,
            'rates' => $rates,
            'rate' => $rate,
        ]);
    }

    public function show($id, GuestShowRequest $request): View
    {
        $validated = $request->validated();

        $product = $this->productRepository->findByUuid($id);
        $rates = $this->currencyRateRepository->pluck();
        $allowedRates = Currency::names();

        $currency = $validated['currency-selector'] ?? null;
        $rate = in_array($currency, $allowedRates, true)
            ? $currency
            : 'USD';

        return view('product.guest.show', [
            'product' => $product,
            'rates' => $rates,
            'rate' => $rate,
        ]);
    }
}
