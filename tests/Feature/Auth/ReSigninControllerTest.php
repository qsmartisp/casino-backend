<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Auth\ReSigninController
 */
class ReSigninControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * POST /api/auth/resignin
     */
    public function testRefreshingTokens(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $userData = $user->toArray();
        $userData['password'] = 'password';

        $response = $this->postJson('/api/auth/signin', $userData);

        $response->assertOk();

        $response = $this->postJson('/api/auth/resignin', [], [
            'Authorization' => 'Bearer ' . $response->json('data.refresh_token'),
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'refresh_token',
                    'expires_at',
                ],
            ]);

        $this->assertNotNull($response->json('data.access_token'));
        $this->assertIsString($response->json('data.access_token'));
    }
}
