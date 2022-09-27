<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @see \App\Models\User
 */
class GameHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $currency = Currency::query()->inRandomOrder()->first();

        return [
            'user_id' => 1,
            'game_id' => $this->faker->word(),
            'game_name' => $this->faker->company(),
            'game_action_id' => $this->faker->word(),
            'currency_id' => $currency->id,
            'currency_code' => $currency->code,
            'balance_before' => $this->faker->numberBetween(10, 500),
            'balance_after' => $this->faker->numberBetween(10, 100),
            'balance_amount' => $this->faker->numberBetween(10, 1000),
            'bet' => $this->faker->numberBetween(10, 1000),
            'cp' => $this->faker->numberBetween(10, 1000),
        ];
    }
}
