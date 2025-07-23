<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\DefaultService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFilterRequest;
use App\Http\Requests\StoreProductRequest;
use App\Jobs\ExportProductJob;
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
        $batchSize = 100;
        $index = 0;

        $lastProduct = Product::latest('id')->first();

        if (! $lastProduct) {
            return redirect()->back()->with('success', 'No products to export.');
        }

        Product::with(['manufacturer', 'services'])
            ->orderBy('id')
            ->chunk($batchSize, function ($products) use (&$index, $lastProduct) {
                $isLast = $products->last()->is($lastProduct);

                ExportProductJob::dispatch($products->toArray(), $index, $isLast);

                $index++;
            });

        return redirect()->back()->with('success', 'Export started!');
    }

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

        return view('product.admin.index', [
            'products' => $products,
            'types' => $types,
        ]);
    }

    public function show($id): View
    {
        $product = Product::where('uuid', $id)->first();

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

        $services = $validated['services'] ?? [];
        $custom_services = $validated['custom_services'] ?? [];

        $this->productCreationService->attachServices($product, $custom_services, $services);

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

        $services = $validated['services'] ?? [];

        $this->productUpdateService->connectServices($product, $services);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }
}
