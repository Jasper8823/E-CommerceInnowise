<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function create(array $data): Product;

    public function update(Product $product, array $data): bool;

    public function findByUuid(string $uuid): ?Product;

    public function latest(): ?Product;

    public function delete(Product $product): void;

    public function chunk(int $size, callable $callback): void;

    public function withRelations(array $relations): Collection;

    public function attachService(Product $product, int $serviceId, float $price, int $daysNeeded): void;

    public function getFilteredQuery(?string $type, ?string $name, ?string $minPrice, ?string $maxPrice, ?string $sort, int $pagination): LengthAwarePaginator;
}
