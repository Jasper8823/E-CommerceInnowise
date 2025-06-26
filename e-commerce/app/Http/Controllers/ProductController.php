<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use function Symfony\Component\Translation\t;

class ProductController extends Controller
{
    public function create(){
        $types = ProductType::all();
        $companies = Company::all();
        return view('product.create', ['types' => $types, 'companies' => $companies]);
    }
    public function index(Request $request) {
        if(Auth::check()) {
            return $this->adminIndex($request);
        }else {
            $query = $this->getBuilder($request);
            $types = ProductType::all();
            $products = $query->paginate(30);
            return view('product.products', ['products' => $products, 'types' => $types]);
        }
    }

    public function adminIndex(Request $request){
        if(Auth::check()) {
            $query = $this->getBuilder($request);
            $products = $query->paginate(30);
            $types = ProductType::all();
            return view('product.products', ['products' => $products, 'types' => $types]);
        }else{
            return redirect('/');
        }
    }

    public function show($id){
        $product =  Product::all()->where('uuId',$id)->first();
        return view('product.show', ['product' => $product]);
    }
    public function getBuilder(Request $request): Builder
    {
        $query = Product::query();

        if ($request->filled('type')) {
            $query->whereHas('type', fn($q) => $q->where('name', $request->type));
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        return $query;
    }
}
