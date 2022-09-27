<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @see \App\Models\Status
 */
class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'prize' => $this->randomNumber(5, false) . ' ' . $this->faker->currencyCode,
        ];
    }
}
