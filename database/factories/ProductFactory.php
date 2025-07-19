<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'product_type_id' => ProductType::inRandomOrder()->value('id') ?? ProductType::factory(),
            'company_id' => Company::inRandomOrder()->value('id') ?? Company::factory(),
            'name' => $this->faker->word,
            'price' => $this->faker->numberBetween(100, 10000),
            'release_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'description' => $this->faker->sentence(10),
        ];
    }
}
