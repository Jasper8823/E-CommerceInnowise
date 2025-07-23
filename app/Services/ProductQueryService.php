<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProductQueryService
{
    private $pagination = 30;

    public function getBuilder(?string $type, ?string $name, ?string $minPrice, ?string $maxPrice, ?string $sort): LengthAwarePaginator
    {
        $query = Product::query();

        if (! is_null($type)) {
            $query->whereHas('type', fn ($q) => $q->where('name', $type));
        }

        if (! is_null($name)) {
            $query->where('name', 'like', '%'.$name.'%');
        }

        if (! is_null($minPrice)) {
            $query->where('price', '>=', $minPrice);
        }

        if (! is_null($maxPrice)) {
            $query->where('price', '<=', $maxPrice);
        }

        if (! is_null($sort)) {
            switch ($sort) {
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

        $query = $query->paginate($this->pagination);

        return $query;
    }
}
