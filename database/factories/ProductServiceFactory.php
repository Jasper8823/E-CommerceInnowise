<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

final class ProductServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => random_int(1, 100),
            'service_id' => random_int(1, 100),
            'price' => $this->faker->numberBetween(100, 5000),
            'days_needed' => $this->faker->numberBetween(1, 30),
        ];
    }
}
