<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use App\Models\ProductType;
use App\Services\ProductQueryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class AdminProductController extends Controller
{
    protected $productQueryService;

    public function __construct(ProductQueryService $productQueryService)
    {
        $this->productQueryService = $productQueryService;
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

        return view('product.admin.productsAdmin', ['products' => $products, 'types' => $types]);
    }

    public function show($id)
    {
        $product = Product::all()->where('uuId', $id)->first();

        return view('product.admin.show', ['product' => $product]);

    }

    public function delete($id)
    {
        $product = Product::all()->where('id', $id)->first();
        $product->delete();

        return redirect('/');
    }
}
