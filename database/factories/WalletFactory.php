<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'holder_type' => User::class,
            'holder_id' => $this->faker->numberBetween(),
            'name' => $this->faker->currencyCode(),
            'slug' => $this->faker->slug(),
            'uuid' => $this->faker->uuid(),
            'description' => $this->faker->text(),
            'meta' => [
                'game_login' => $this->faker->userName(),
                'game_password' => $this->faker->password(10),
            ],
            'balance' => 100,
            'decimal_places' => $this->faker->numberBetween(0, 5),
        ];
    }
}
