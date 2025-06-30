<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AuthorisedUser;
use Illuminate\Database\Eloquent\Factories\Factory;

final class AuthorisedUserFactory extends Factory
{
    protected $model = AuthorisedUser::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'company_id' => random_int(1, 10),
            'isAdmin' => $this->faker->boolean(5),
        ];
    }
}
