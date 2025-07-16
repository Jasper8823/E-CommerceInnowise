<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class ProductQueryService
{
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
