<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

final class ProductCreationService
{
    public function attachServices(Product $product, $custom_services, $services): void
    {
        if (isEmpty($custom_services)) {
            foreach ($custom_services as $index => $custom_service) {
                $validator = Validator::make($custom_service, [
                    'name' => 'required|string|min:2|max:64',
                    'price' => 'required|numeric|min:0',
                    'daysNeeded' => 'required|integer|min:0',
                ]);

                if ($validator->fails()) {
                    continue;
                }

                $service = new Service();
                $service->name = $custom_service['name'];
                $service->save();

                $product->services()->attach($service->id, [
                    'price' => $custom_service['price'],
                    'days_needed' => $custom_service['daysNeeded'],
                ]);
            }
        }

        foreach ($services as $serviceName => $serviceData) {
            if (! isset($serviceData['enabled'])) {
                continue;
            }

            $validator = Validator::make($serviceData + ['name' => $serviceName], [
                'price' => 'required|numeric|min:0',
                'daysNeeded' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                continue;
            }

            $service = Service::where('name', $serviceName)->first();

            if (! $service) {
                $service = new Service();
                $service->name = $serviceName;
                $service->save();
            }

            $product->services()->attach($service->id, [
                'price' => $serviceData['price'],
                'days_needed' => $serviceData['daysNeeded'],
            ]);
        }
    }
}
