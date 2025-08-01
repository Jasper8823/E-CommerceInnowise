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
                $validator = Validator::make($customService, [
                    'name' => 'required|string|min:2|max:64',
                    'price' => 'required|numeric|min:0',
                    'daysNeeded' => 'required|integer|min:0',
                ]);

                if ($validator->fails()) {
                    continue;
                }

                $service = $this->serviceRepository->firstOrCreate(['name' => $customService['name']]);

                $this->productRepository->attachService($product, $service->id, (float) $customService['price'], (int) $customService['daysNeeded']);
            }
        }

        foreach ($services as $serviceName => $serviceData) {
            if (! isset($serviceData['name'])) {
                continue;
            }

            $validator = Validator::make($serviceData + ['name' => $serviceName], [
                'price' => 'required|numeric|min:0',
                'daysNeeded' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                continue;
            }

            $service = $this->serviceRepository->firstOrCreate(['name' => $serviceData['name']]);

            $this->productRepository->attachService($product, $service->id, (float) $serviceData['price'], (int) $serviceData['daysNeeded']);
        }
    }
}
