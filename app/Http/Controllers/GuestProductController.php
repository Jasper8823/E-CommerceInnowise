<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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

    public function index(Request $request)
    {
        $query = $this->productQueryService->getBuilder($request);
        $types = ProductType::all();
        $products = $query->paginate(30);

        return view('product.guest.products', ['products' => $products, 'types' => $types]);
    }

    public function show($id)
    {
        $product = Product::all()->where('uuId', $id)->first();

        return view('product.guest.show', ['product' => $product]);
    }
}
