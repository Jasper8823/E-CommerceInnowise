<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class AdminController extends Controller
{
    public function create()
    {
        if(Auth::check()) {
            $types = ProductType::all();
            $companies = Company::all();

            return view('product.create', ['types' => $types, 'companies' => $companies]);
        }else{
            abort(404);
        }
    }

    public function index(Request $request)
    {
        if (Auth::check()) {
            $query = $this->getBuilder($request);
            $products = $query->paginate(30);
            $types = ProductType::all();

            return view('product.products', ['products' => $products, 'types' => $types]);
        }else{
            abort(404);
        }
    }

    public function show($id)
    {
        if(Auth::check()) {
            $product = Product::all()->where('uuId', $id)->first();

            return view('product.show', ['product' => $product]);
        }else{
            abort(404);
        }
    }

    public function delete($id)
    {
        if (Auth::check()) {
            $product = Product::all()->where('id', $id)->first();
            $product->delete();
            return redirect('/');
        }else{
            abort(404);
        }
    }

    public function getBuilder(Request $request): Builder
    {
        $query = Product::query();

        if ($request->filled('type')) {
            $query->whereHas('type', fn ($q) => $q->where('name', $request->type));
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'release_asc':
                    $query->orderBy('release_date');
                    break;
                case 'release_desc':
                    $query->orderBy('release_date', 'desc');
                    break;
                default:
                    break;
            }
        } else {
            $query->orderBy('name');
        }

        return $query;
    }

}
