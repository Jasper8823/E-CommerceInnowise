<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    public function findByUuid(string $uuid): ?Product
    {
        return Product::where('uuid', $uuid)->first();
    }

    public function latest(): ?Product
    {
        return Product::latest('id')->first();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function chunk(int $size, callable $callback): void
    {
        Product::with(['manufacturer', 'services'])
            ->orderBy('id')
            ->chunk($size, $callback);
    }

    public function withRelations(array $relations): Collection
    {
        return Product::with($relations)->get();
    }

    public function attachService(Product $product, int $serviceId, float $price, int $daysNeeded): void
    {
        $product->services()->attach($serviceId, [
            'price' => $price,
            'days_needed' => $daysNeeded,
        ]);
    }

    public function getFilteredQuery(?string $type, ?string $name, ?string $minPrice, ?string $maxPrice, ?string $sort): Builder
    {
        $query = Product::query();

        if ($type !== null) {
            $query->whereHas('type', fn ($q) => $q->where('name', $type));
        }

        if ($name !== null) {
            $query->where('name', 'like', '%'.$name.'%');
        }

        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'release_asc' => $query->orderBy('release_date'),
            'release_desc' => $query->orderBy('release_date', 'desc'),
            default => $query->orderBy('name'),
        };

        return $query;
    }
}
