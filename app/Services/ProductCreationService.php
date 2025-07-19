<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

final class ProductCreationService
{
    public function attachServices(Product $product, Request $request): void
    {
        if ($request->filled('custom_services')) {
            $customServices = $request->input('custom_services');
            foreach ($customServices as $index => $customService) {
                $validator = Validator::make($customService, [
                    'name' => 'required|string|min:2|max:64',
                    'price' => 'required|numeric|min:0',
                    'daysNeeded' => 'required|integer|min:0',
                ]);

                if ($validator->fails()) {
                    continue;
                }

                $service = new Service();
                $service->name = $customService['name'];
                $service->save();

                $product->services()->attach($service->id, [
                    'price' => $customService['price'],
                    'days_needed' => $customService['daysNeeded'],
                ]);
            }
        }

        $services = $request->input('services', []);
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
