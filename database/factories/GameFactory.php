<?php

namespace Database\Factories;

use App\Models\Aggregator;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @mixin \App\Models\Game
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(),

            'slug' => $this->faker->unique()->slug(),

            'external_id' => $this->faker->unique()->slug(),

            'provider_id' => Provider::factory(),
            'aggregator_id' => Aggregator::factory(),

            'name' => $this->faker->name(),

            'is_desktop' => $this->faker->boolean(99),
            'is_mobile' => $this->faker->boolean(99),

            'min_bet' => $this->faker->numberBetween(),
            'max_bet' => $this->faker->numberBetween(),

            'status' => $this->faker->randomElement([
                'enabled',
                //'disabled',
            ]),

            'has_demo' => $this->faker->boolean(99),

            'sort_order' => $this->faker->numberBetween(),
        ];
    }
}
