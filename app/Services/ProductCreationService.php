<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Repositories\ServiceRepository;
use Illuminate\Support\Facades\Validator;

final class ProductCreationService
{
    public function __construct(private ServiceRepository $serviceRepository,
        private ProductRepository $productRepository) {}

    public function attachServices(Product $product, array $customServices, array $services): void
    {
        if (! empty($customServices)) {
            foreach ($customServices as $customService) {

                $service = $this->serviceRepository->firstOrCreate(['name' => $customService['name']]);

                $this->productRepository->attachService($product, $service->id, (float) $customService['price'], (int) $customService['daysNeeded']);
            }
        }

        foreach ($services as $serviceName => $serviceData) {
            if (! isset($serviceData['name'])) {
                continue;
            }

            $service = $this->serviceRepository->firstOrCreate(['name' => $serviceData['name']]);

            $this->productRepository->attachService($product, $service->id, (float) $serviceData['price'], (int) $serviceData['daysNeeded']);
        }
    }
}
