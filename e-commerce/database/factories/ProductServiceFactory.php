<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
class ProductServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => random_int(1,100),
            'service_id' => random_int(1,100),
            'price' => $this->faker->numberBetween(100, 5000),
            'daysNeeded' => $this->faker->numberBetween(1, 30),
        ];
    }
}
