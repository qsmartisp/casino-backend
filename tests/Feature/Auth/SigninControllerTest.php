<?php

namespace Tests\Feature\Auth;

use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\FailedValidation;

/**
 * @coversDefaultClass \App\Http\Controllers\Auth\SigninController
 */
class SigninControllerTest extends TestCase
{
    use FailedValidation;
    use RefreshDatabase;
    use WithFaker;

    /**
     * /api/auth/signin
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function testValidationErrors(): void
    {
        $uniqueUser = User::factory()->create();
        $errorCases = [
            [
                // email required
                'password' => 'password',
            ],
            [
                // email must be a valid email address
                'email' => $this->faker->userName,
                'password' => 'password',
            ],
            [
                // email exists
                'email' => $this->faker->unique()->safeEmail,
                'password' => 'password',
            ],
            [
                // password required
                'email' => $uniqueUser->email,
            ],
            [
                // password string
                'email' => $uniqueUser->email,
                'password' => $this->faker->numberBetween(10000000, 999999999),
            ],
            [
                // password min:8
                'email' => $uniqueUser->email,
                'password' => $this->faker->password(1, 7),
            ],
            [
                // password max:24
                'email' => $uniqueUser->email,
                'password' => $this->faker->password(25, 255),
            ],
        ];

        foreach ($errorCases as $errorCase) {
            $response = $this->postJson('/api/auth/signin', $errorCase);
            $this->responseValidationFailedTest($response);
        }
    }

    public function testSuccessScenario()
    {
        $user = User::factory()->create()->toArray();
        $user['password'] = 'password';

        $response = $this->postJson('/api/auth/signin', $user);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'refresh_token',
                    'expires_at',
                ],
            ]);

        $data = $response->decodeResponseJson();

        $this->assertNotNull($data['data']['access_token']);
        $this->assertIsString($data['data']['access_token']);
    }

    /**
     * /api/auth/signin
     *
     * @return void
     */
    public function testWrongPasswordScenario(): void
    {
        $user = User::factory()->create()->toArray();
        $user['password'] = 'wrong_password';

        $response = $this->postJson('/api/auth/signin', $user);

        $response->assertStatus(403)
            ->assertJsonFragment([
                'message' => 'Incorrect password.',
            ]);
    }
}
