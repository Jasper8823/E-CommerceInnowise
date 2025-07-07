<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class ProductUpdateService
{
    public function connectServices(Request $request, Product $product)
    {
        foreach ($request->input('services', []) as $serviceData) {
            if (!isset($serviceData['name'], $serviceData['price'], $serviceData['daysNeeded'])) {
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
