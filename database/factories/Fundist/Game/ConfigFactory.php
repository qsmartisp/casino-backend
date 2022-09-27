<?php

namespace Database\Factories\Fundist\Game;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @mixin \App\Models\Fundist\Game\Config
 */
class ConfigFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $slug = $this->faker->unique()->slug();

        return [
            'slug' => $this->faker->slug,
            'id' => $this->faker->numberBetween(),
            'system_id' => $this->faker->numberBetween(),
            'subsystem_id' => $this->faker->numberBetween(),
            'page_code' => $slug,
            'mobile_page_code' => $slug . '-mobile',
        ];
    }
}
