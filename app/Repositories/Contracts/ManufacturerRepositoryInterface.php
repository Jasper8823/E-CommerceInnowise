<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Manufacturer;
use Illuminate\Support\Collection;

interface ManufacturerRepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): Manufacturer;
}
