<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Manufacturer;
use App\Repositories\Contracts\ManufacturerRepositoryInterface;
use Illuminate\Support\Collection;

class ManufacturerRepository implements ManufacturerRepositoryInterface
{
    public function all(): Collection
    {
        return Manufacturer::all();
    }

    public function create(array $data): Manufacturer
    {
        return Manufacturer::create($data);
    }
}
