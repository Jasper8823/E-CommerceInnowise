<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
