<?php

namespace Database\Factories;

use App\Models\Login;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Random\RandomException;
use Illuminate\Support\Facades\Hash;

class LoginFactory extends Factory
{
    protected $model = Login::class;

    //public static $user_id = 0;

    public function definition(): array
    {
        //LoginFactory::$user_id++;
        return [
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            //'authorised_user_id' => LoginFactory::$user_id
        ];
    }
}
