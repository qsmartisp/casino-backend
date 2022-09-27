<?php

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\SignupController;
use App\Models\User;
use App\Services\Fundist\FundistService;
use App\Services\Fundist\Sender;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;
use Tests\TestCase;
use Tests\Traits\FailedValidation;

/**
 * @coversDefaultClass \App\Http\Controllers\Auth\SignupController
 */
class SignupControllerTest extends TestCase
{
    use FailedValidation;
    use RefreshDatabase;
    use WithFaker;

    /**
     * /api/auth/signup
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function testSignup(): void
    {
        $this->app
            ->when(Sender::class)
            ->needs(ClientInterface::class)
            ->give(fn() => $this->getClient());
        $this->app
            ->when(Sender::class)
            ->needs(LoggerInterface::class)
            ->give(fn() => $this->getLogger());

        $this->app
            ->when(SignupController::class)
            ->needs(FundistService::class)
            ->give(fn() => $this->getFundistApi());

        $this->app
            ->when(SignupController::class)
            ->needs(DatabaseServiceInterface::class)
            ->give(fn() => $this->getDatabaseService());

        $uniqueUser = User::factory()->create();
        $errorCases = [
            [
                // email required
                'password' => $this->faker->password(8, 24),
            ],
            [
                // email must be a valid email address
                'email' => $this->faker->userName,
                'password' => $this->faker->password(8, 24),
            ],
            [
                // email unique
                'email' => $uniqueUser->email,
                'password' => $this->faker->password(8, 24),
            ],
            [
                // password required
                'email' => $this->faker->unique()->safeEmail,
            ],
            [
                // password string
                'email' => $this->faker->unique()->safeEmail,
                'password' => $this->faker->numberBetween(10000000, 999999999),
            ],
            [
                // password min:8
                'email' => $this->faker->unique()->safeEmail,
                'password' => $this->faker->password(1, 7),
            ],
            [
                // password max:24
                'email' => $this->faker->unique()->safeEmail,
                'password' => $this->faker->password(25, 255),
            ],
        ];

        foreach ($errorCases as $errorCase) {
            $response = $this->postJson('/api/auth/signup', $errorCase);
            $this->responseValidationFailedTest($response);
        }

        $user = User::factory()->make()->toArray();
        $user['password'] = 'password';

        $response = $this->postJson('/api/auth/signup', $user);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'email',
                ],
            ]);

        $this->assertDatabaseHas('users', ['email' => $user['email']]);
    }

    private function getClient(): MockInterface
    {
        return $this->mock(
            abstract: ClientInterface::class,
            mock: static function (MockInterface $mock) {
                $mock->shouldReceive('request')
                    ->withAnyArgs()
                    ->once()
                    ->andReturn(new Response(body: "1"));
            },
        );
    }

    private function getLogger(): MockInterface
    {
        return $this->mock(
            abstract: LoggerInterface::class,
            mock: static function (MockInterface $mock) {
                $mock->shouldReceive('debug')
                    ->once();
            },
        );
    }

    private function getDatabaseService(): MockInterface
    {
        return $this->mock(DatabaseServiceInterface::class);
    }

    private function getFundistApi(): MockInterface
    {
        return $this->mock(FundistService::class);
    }
}
