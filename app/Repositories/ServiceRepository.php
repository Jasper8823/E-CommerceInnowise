<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Service;
use App\Repositories\Contracts\ServiceRepositoryInterface;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function create(array $data): Service
    {
        return Service::create($data);
    }

    public function findByName(string $name): ?Service
    {
        return Service::where('name', $name)->first();
    }

    public function firstOrCreate(array $attributes): Service
    {
        return Service::firstOrCreate($attributes);
    }
}
