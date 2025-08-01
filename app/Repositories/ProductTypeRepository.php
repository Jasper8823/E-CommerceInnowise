<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ProductType;
use App\Repositories\Contracts\ProductTypeRepositoryInterface;
use Illuminate\Support\Collection;

class ProductTypeRepository implements ProductTypeRepositoryInterface
{
    public function all(): Collection
    {
        return ProductType::all();
    }

    public function create(array $data): ProductType
    {
        return ProductType::create($data);
    }
}
