<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\DefaultService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFilterRequest;
use App\Http\Requests\StoreProductRequest;
use App\Jobs\ExportProductJob;
use App\Models\CurrencyRate;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\ProductType;
use App\Services\ProductCreationService;
use App\Services\ProductQueryService;
use App\Services\ProductUpdateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

final class AdminProductController extends Controller
{
    public function __construct(
        protected ProductQueryService $productQueryService,
        protected ProductCreationService $productCreationService,
        protected ProductUpdateService $productUpdateService
    ) {}

    public function create(): View
    {
        $types = ProductType::all();
        $manufacturers = Manufacturer::all();

        return view('product.admin.create', ['types' => $types, 'manufacturers' => $manufacturers]);
    }

    public function export(): RedirectResponse
    {
        $products = Product::all();
        $chunks = $products->chunk(100);
        $lastIndex = $chunks->count() - 1;

        foreach ($chunks as $index => $chunk) {
            $isLast = $index === $lastIndex;
            $isFirst = $index === 0;
            ExportProductJob::dispatch($chunk->toArray(), $isFirst, $isLast);
        }

        return redirect()->back()->with('success', 'Export started!');
    }

    public function index(ProductFilterRequest $request): View
    {
        $type = $request->input('type');
        $name = $request->input('name');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sort = $request->input('sort');

        $products = $this->productQueryService->getBuilder($type, $name, $minPrice, $maxPrice, $sort);
        $types = ProductType::all();

        return view('product.admin.index', [
            'products' => $products,
            'types' => $types,
        ]);
    }

    public function show($id): View
    {
        $product = Product::where('uuid', $id)->first();
        $ratesDB = CurrencyRate::all();
        $rates = [];

        foreach ($ratesDB as $rate) {
            $rates[$rate->currency] = $rate->rate;
        }

        return view('product.admin.show', ['product' => $product]);

    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $product = new Product([
            'name' => $validated['name'],
            'uuid' => Str::uuid(),
            'product_type_id' => $validated['product_type_id'],
            'price' => $validated['price'],
            'release_date' => $validated['releaseDate'],
            'manufacturer_id' => $validated['manufacturer_id'],
            'description' => $validated['description'] ?? null,
        ]);

        $product->save();

        $this->productCreationService->attachServices($product, $request);

        return redirect('/admin/products')->with('success', 'Product created successfully!');
    }

    public function destroy($id): RedirectResponse
    {
        $product = Product::where('id', $id)->first();
        $product->delete();

        return redirect('/');
    }

    public function edit($id): View
    {
        $product = Product::with(['type', 'manufacturer', 'services'])->where('uuid', $id)->firstOrFail();
        $types = ProductType::all();
        $manufacturers = Manufacturer::all();
        $defaultServices = DefaultService::names();

        return view('product.admin.edit', compact('product', 'types', 'manufacturers', 'defaultServices'));
    }

    public function update(StoreProductRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();

        $product = Product::where('uuid', $id)->firstOrFail();

        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'release_date' => $validated['releaseDate'],
            'product_type_id' => $validated['product_type_id'],
            'manufacturer_id' => $validated['manufacturer_id'],
        ]);

        $product->services()->detach();

        $this->productUpdateService->connectServices($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }
}
