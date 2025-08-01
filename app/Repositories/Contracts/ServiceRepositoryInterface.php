<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Service;

interface ServiceRepositoryInterface
{
    public function create(array $data): Service;

    public function findByName(string $name): ?Service;

    public function firstOrCreate(array $attributes): Service;
}
