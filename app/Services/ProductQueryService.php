<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProductQueryService
{
    private int $pagination = 30;

    public function __construct(private ProductRepository $productRepository) {}

    public function getBuilder(?string $type, ?string $name, ?string $minPrice, ?string $maxPrice, ?string $sort): LengthAwarePaginator
    {
        $query = $this->productRepository->getFilteredQuery($type, $name, $minPrice, $maxPrice, $sort);

        return $query->paginate($this->pagination);
    }
}
