<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @see \App\Models\User
 */
class GameLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        /** @var Currency $currency */
        $currency = Currency::factory();

        /** @var Game $game */
        $game = Game::factory();

        return [
            'user_id' => User::factory(),

            'game_id' => $game->id,
            'game_name' => $game->name,

            'currency_id' => $currency->id,
            'currency_code' => $currency->code,

            'balance_before' => $this->faker->numberBetween(10, 500),
            'balance_after' => $this->faker->numberBetween(10, 100),
            'balance_amount' => $this->faker->numberBetween(10, 1000),
        ];
    }
}
