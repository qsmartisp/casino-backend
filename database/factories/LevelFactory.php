<?php

namespace Database\Factories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @see \App\Models\Status
 */
class LevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'status_id' => Status::factory(),
            'number' => $this->faker->unique()->numberBetween(1, 28),
            'prize_amount' => $this->faker->randomNumber(4, false),
            'prize_type' => $this->faker->randomElement(['FS', 'EUR']),
            'cp' => $this->faker->unique()->numberBetween(100, 2000000),
        ];
    }
}
