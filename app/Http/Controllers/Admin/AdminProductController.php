<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\DefaultService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFilterRequest;
use App\Http\Requests\StoreProductRequest;
use App\Jobs\ExportProductJob;
use App\Repositories\Contracts\ManufacturerRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\ProductTypeRepositoryInterface;
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
        protected ProductUpdateService $productUpdateService,
        protected ProductRepositoryInterface $productRepository,
        protected ManufacturerRepositoryInterface $manufacturerRepository,
        protected ProductTypeRepositoryInterface $productTypeRepository
    ) {}

    public function create(): View
    {
        $types = $this->productTypeRepository->all();
        $manufacturers = $this->manufacturerRepository->all();

        return view('product.admin.create', compact('types', 'manufacturers'));
    }

    public function export(): RedirectResponse
    {
        $batchSize = 100;
        $index = 0;

        $lastProduct = $this->productRepository->latest();

        if (! $lastProduct) {
            return redirect()->back()->with('success', 'No products to export.');
        }

        $this->productRepository->chunk($batchSize, function ($products) use (&$index, $lastProduct) {
            $isLast = $products->last()->is($lastProduct);

            ExportProductJob::dispatch($products->toArray(), $index, $isLast);
            $index++;
        });

        return redirect()->back()->with('success', 'Export started!');
    }

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

        return view('product.admin.index', compact('products', 'types'));
    }

    public function show($id): View
    {
        $product = $this->productRepository->findByUuid($id);

        return view('product.admin.show', ['product' => $product]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $product = $this->productRepository->create([
            'name' => $validated['name'],
            'uuid' => Str::uuid(),
            'product_type_id' => $validated['product_type_id'],
            'price' => $validated['price'],
            'release_date' => $validated['releaseDate'],
            'manufacturer_id' => $validated['manufacturer_id'],
            'description' => $validated['description'] ?? null,
        ]);

        $services = $validated['services'] ?? [];
        $customServices = $validated['custom_services'] ?? [];

        $this->productCreationService->attachServices($product, $customServices, $services);

        return redirect('/admin/products')->with('success', 'Product created successfully!');
    }

    public function destroy($id): RedirectResponse
    {
        $product = $this->productRepository->findByUuid($id);
        if ($product) {
            $this->productRepository->delete($product);
        }

        return redirect('/');
    }

    public function edit($id): View
    {
        $product = $this->productRepository->findByUuid($id);
        $types = $this->productTypeRepository->all();
        $manufacturers = $this->manufacturerRepository->all();
        $defaultServices = DefaultService::names();

        return view('product.admin.edit', compact('product', 'types', 'manufacturers', 'defaultServices'));
    }

    public function update(StoreProductRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();

        $product = $this->productRepository->findByUuid($id);

        $this->productRepository->update($product, [
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
