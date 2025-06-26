<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Login;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Service;
use App\Models\AuthorisedUser;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Company::factory()->count(10)->create();
        ProductType::factory()->count(10)->create();
        //    AuthorisedUser::factory()->count(100)->create();
        Login::factory()->count(100)->create();
        Product::factory()->count(100)->create();
        Service::factory()->count(100)->create();


        $products = Product::all();
        $services = Service::all();

        foreach ($products as $product) {
            $randomServices = $services->random(rand(1, 5));
            foreach ($randomServices as $service) {
                $product->services()->attach($service->id, [
                    'price' => fake()->numberBetween(100, 5000),
                    'daysNeeded' => fake()->numberBetween(1, 30),
                ]);
            }
        }
    }
}
