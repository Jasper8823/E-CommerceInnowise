<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\ProductType;
use Illuminate\Support\Collection;

interface ProductTypeRepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): ProductType;
}
