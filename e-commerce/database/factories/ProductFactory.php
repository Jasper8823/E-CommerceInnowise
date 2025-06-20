<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Random\RandomException;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @throws RandomException
     */
    public function definition(): array
    {
        return [
            'uuId' => Str::uuid(),
            'product_type_id' => random_int(1,10),
            'name' => $this->faker->word,
            'price' => $this->faker->numberBetween(100, 10000),
            'releaseDate' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'company_id' => random_int(1,10),
            'description' => $this->faker->sentence(10),
        ];
    }
}
