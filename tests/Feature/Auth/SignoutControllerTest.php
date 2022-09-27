<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Auth\SignoutController
 */
class SignoutControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * /api/auth/signup
     *
     * @return void
     * @throws \Throwable
     */
    public function testSignup(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/auth/signout');

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', ['email' => $user['email']]);
    }
}
