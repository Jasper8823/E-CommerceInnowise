<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Service;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Manufacturer::factory()->count(10)->create();
        ProductType::factory()->count(10)->create();
        Product::factory()->count(1000)->create();
        Service::factory()->count(100)->create();

        $products = Product::all();
        $services = Service::all();

        foreach ($products as $product) {
            $randomServices = $services->random(rand(1, 5));
            foreach ($randomServices as $service) {
                $product->services()->attach($service->id, [
                    'price' => fake()->numberBetween(100, 5000),
                    'days_needed' => fake()->numberBetween(1, 30),
                ]);
            }
        }
    }
}
