<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use App\Models\ProductType;
use App\Services\ProductCreationService;
use App\Services\ProductQueryService;
use App\Services\ProductUpdateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

final class AdminProductController extends Controller
{
    protected ProductQueryService $productQueryService;

    protected ProductCreationService $productCreationService;

    protected ProductUpdateService $productUpdateService;

    public function __construct(ProductQueryService $productQueryService, ProductCreationService $productCreationService, ProductUpdateService $productUpdateService)
    {
        $this->productQueryService = $productQueryService;
        $this->productUpdateService = $productUpdateService;
        $this->productCreationService = $productCreationService;
    }

    public function create()
    {
        $types = ProductType::all();
        $companies = Company::all();

        return view('product.admin.create', ['types' => $types, 'companies' => $companies]);
    }

    public function index(Request $request)
    {
        $query = $this->productQueryService->getBuilder($request);
        $products = $query->paginate(30);
        $types = ProductType::all();

        return view('product.admin.products', ['products' => $products, 'types' => $types]);
    }

    public function show($id)
    {
        $product = Product::all()->where('uuId', $id)->first();

        return view('product.admin.show', ['product' => $product]);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'releaseDate' => 'required|date',
            'description' => 'required|string',
            'services' => 'nullable|array',
            'custom_services' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/products/create')
                ->withErrors($validator)
                ->withInput();
        }

        $errorMassage = $this->productCreationService->validateRequest($request);

        if ($errorMassage !== null) {
            return redirect('/admin/products/create')
                ->withErrors($errorMassage)
                ->withInput();
        }

        $company_productType = $this->productCreationService->createConnectedObjects($request);

        $product = new Product();
        $product->name = $request->input('name');
        $product->uuId = Str::uuid();
        $product->product_type_id = $company_productType[1]->id;
        $product->price = $request->input('price');
        $product->release_date = $request->input('releaseDate');
        $product->company_id = $company_productType[0]->id;
        $product->description = $request->input('description');
        $product->save();

        $this->productCreationService->attachServices($product, $request);

        return redirect('/admin/products')->with('success', 'Product created successfully!');
    }

    public function delete($id)
    {
        $product = Product::all()->where('id', $id)->first();
        $product->delete();

        return redirect('/');
    }

    public function edit($id)
    {
        $product = Product::with(['type', 'manufacturer', 'services'])->where('uuId', $id)->firstOrFail();
        $types = ProductType::all();
        $companies = Company::all();
        $defaultServices = ['Service', 'Delivery', 'Installation', 'Configuration'];

        return view('product.admin.edit', compact('product', 'types', 'companies', 'defaultServices'));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::where('uuId', $id)->firstOrFail();

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'release_date' => $request->releaseDate,
            'product_type_id' => $request->product_type_id ?? ProductType::firstOrCreate(['name' => $request->new_product_type])->id,
            'company_id' => $request->company_id ?? Company::firstOrCreate(['name' => $request->new_company])->id,
        ]);

        $product->services()->detach();

        $this->productUpdateService->connectServices($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }
}
