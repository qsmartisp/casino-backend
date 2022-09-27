<?php

namespace Database\Factories\Fundist\User;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CredentialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'currency_id' => Currency::factory(),
            'login' => $this->faker->userName(),
            'password' => $this->faker->password(10),
        ];
    }
}
