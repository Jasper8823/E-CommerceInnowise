<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\Service;

final class ProductUpdateService
{
    public function connectServices(Product $product, $services): void
    {
        foreach ($services as $serviceData) {
            if (! isset($serviceData['name'], $serviceData['price'], $serviceData['daysNeeded'])) {
                continue;
            }
            $service = Service::firstOrCreate(['name' => $serviceData['name']]);
            $product->services()->attach($service->id, [
                'price' => $serviceData['price'],
                'days_needed' => $serviceData['daysNeeded'],
            ]);
        }
    }
}
