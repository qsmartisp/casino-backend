<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Traits\FailedValidation;
use Tests\Traits\SuccessResponse;

/**
 * @coversDefaultClass \App\Http\Controllers\ProfileController
 */
class EstchangeControllerTest extends TestCase
{
    use FailedValidation;
    use RefreshDatabase;
    use SuccessResponse;
    use WithFaker;

    /**
     * /api/profile
     *
     * @return void
     */
    public function testGetRates(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/withdrawal/estchange/rate/USD');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'currency',
                    'rates' => [
                        [
                            'currency',
                            'coin',
                            'coin_name',
                            'rate',
                        ],
                        [
                            'currency',
                            'coin',
                            'coin_name',
                            'rate',
                        ],
                    ],
                ],
            ]);
    }

}
