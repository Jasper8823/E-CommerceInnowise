<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\DefaultService;
use App\Http\Requests\ProductFilterRequest;
use App\Http\Requests\StoreProductRequest;
use App\Jobs\ExportProductJob;
use App\Models\Company;
use App\Models\CurrencyRate;
use App\Models\Product;
use App\Models\ProductType;
use App\Services\ProductCreationService;
use App\Services\ProductQueryService;
use App\Services\ProductUpdateService;
use Illuminate\Support\Str;

final class AdminProductController extends Controller
{
    public function __construct(
        protected ProductQueryService $productQueryService,
        protected ProductCreationService $productCreationService,
        protected ProductUpdateService $productUpdateService
    ) {}

    public function create()
    {
        $types = ProductType::all();
        $companies = Company::all();

        return view('product.admin.create', ['types' => $types, 'companies' => $companies]);
    }

    public function export()
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

    public function index(ProductFilterRequest $request)
    {
        $query = $this->productQueryService->getBuilder($request);
        $products = $query->paginate(30);
        $types = ProductType::all();

        return view('product.admin.index', [
            'products' => $products,
            'types' => $types,
        ]);
    }

    public function show($id)
    {
        $product = Product::all()->where('uuid', $id)->first();
        $ratesDB = CurrencyRate::all();
        $rates = [];

        foreach ($ratesDB as $rate) {
            $rates[$rate->currency] = $rate->rate;
        }

        return view('product.admin.show', ['product' => $product]);

    }

    public function store(StoreProductRequest $request)
    {
        $product_type_id = $request->product_type_id;
        if (! $product_type_id && $request->filled('new_product_type')) {
            $product_type = ProductType::firstOrCreate(['name' => $request->new_product_type]);
            $product_type_id = $product_type->id;
        }

        $company_id = $request->company_id;
        if (! $company_id && $request->filled('new_company')) {
            $company = Company::firstOrCreate(['name' => $request->new_company]);
            $company_id = $company->id;
        }

        $product = new Product([
            'name' => $request->input('name'),
            'uuid' => Str::uuid(),
            'product_type_id' => $product_type_id,
            'price' => $request->input('price'),
            'release_date' => $request->input('releaseDate'),
            'company_id' => $company_id,
            'description' => $request->input('description'),
        ]);

        $product->save();

        $this->productCreationService->attachServices($product, $request);

        return redirect('/admin/products')->with('success', 'Product created successfully!');
    }

    public function destroy($id)
    {
        $product = Product::where('id', $id)->first();
        $product->delete();

        return redirect('/');
    }

    public function edit($id)
    {
        $product = Product::with(['type', 'manufacturer', 'services'])->where('uuid', $id)->firstOrFail();
        $types = ProductType::all();
        $companies = Company::all();
        $defaultServices = DefaultService::names();

        return view('product.admin.edit', compact('product', 'types', 'companies', 'defaultServices'));
    }

    public function update(StoreProductRequest $request, string $id)
    {
        $product = Product::where('uuid', $id)->firstOrFail();

        $product_type_id = $request->product_type_id;
        if (! $product_type_id && $request->filled('new_product_type')) {
            $product_type = ProductType::firstOrCreate(['name' => $request->new_product_type]);
            $product_type_id = $product_type->id;
        }

        $company_id = $request->company_id;
        if (! $company_id && $request->filled('new_company')) {
            $company = Company::firstOrCreate(['name' => $request->new_company]);
            $company_id = $company->id;
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'release_date' => $request->releaseDate,
            'product_type_id' => $product_type_id,
            'company_id' => $company_id,
        ]);

        $product->services()->detach();

        $this->productUpdateService->connectServices($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }
}
