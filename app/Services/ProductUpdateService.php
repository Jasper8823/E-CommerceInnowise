<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\ServiceRepositoryInterface;

final class ProductUpdateService
{
    public function __construct(
        private ServiceRepositoryInterface $serviceRepository,
        private ProductRepositoryInterface $productRepository
    ) {}

    public function connectServices(Product $product, array $services): void
    {
        foreach ($services as $serviceData) {
            if (! isset($serviceData['name'], $serviceData['price'], $serviceData['daysNeeded'])) {
                continue;
            }

            $service = $this->serviceRepository->firstOrCreate(['name' => $serviceData['name']]);

            $this->productRepository->attachService($product, $service->id, (float) $serviceData['price'], (int) $serviceData['daysNeeded']);
        }
    }
}
