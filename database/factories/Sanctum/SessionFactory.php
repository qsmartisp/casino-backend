<?php

namespace Database\Factories\Sanctum;

use App\Models\Country;
use App\Models\Sanctum\PersonalAccessToken;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'access_token_id' => PersonalAccessToken::factory(['name' => 'access_token']),
            'refresh_token_id' => PersonalAccessToken::factory(['name' => 'refresh_token']),
            'country_id' => Country::factory(),
            'ip' => $this->faker->ipv4(),
            'browser' => $this->faker->userAgent(),
        ];
    }
}
