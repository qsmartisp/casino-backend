<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepositFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'transaction_id' => 'todo', // todo
            'external_transaction_id' => 'todo', // todo
            'status' => $this->faker->randomElement([
                'pending',
                'accepted',
                'rejected',
            ]),
            'currency' => Currency::factory()->create()->code, // $this->faker->currencyCode
            'fee' => $this->faker->randomFloat(),
        ];
    }
}
