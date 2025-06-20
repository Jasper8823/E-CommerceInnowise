<?php

namespace Database\Factories;

use App\Models\AuthorisedUser;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AuthorisedUserFactory extends Factory
{
    protected $model = AuthorisedUser::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'company_id' => random_int(1,10),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'isAdmin' => $this->faker->boolean(5),
        ];
    }
}
