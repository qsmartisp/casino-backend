<?php

namespace Tests\Feature;

use App\Http\Controllers\ProfileController;
use App\Models\User;
use App\Models\Wallet;
use App\Services\Local\Repositories\WalletRepository;
use Bavix\Wallet\Services\WalletServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\Traits\FailedValidation;
use Tests\Traits\SuccessResponse;

/**
 * @coversDefaultClass \App\Http\Controllers\ProfileController
 */
class ProfileControllerTest extends TestCase
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
    public function testShowingUserInfo(): void
    {
        /** @var Wallet $currency */
        $wallet = Wallet::factory()->create();

        $this->app
            ->when(ProfileController::class)
            ->needs(WalletRepository::class)
            ->give(fn() => $this->getWalletRepository($wallet));

        $this->getWalletService($wallet);

        /** @var User $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/profile');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'email',
                    'nickname',
                    'first_name',
                    'last_name',
                    'date_of_birth',
                    'gender',
                    'country' => [
                        'id',
                        'name',
                    ],
                    'city',
                    'address',
                    'postal_code',
                    'phone',
                    'subscription_by_email',
                    'subscription_by_sms',
                    'default_currency' => [
                        'id',
                        'code',
                        'name',
                    ],
                    'balance',
                    'is_disabled',
                    'verification_status',
                    'self_exclusion_until',
                    //'wallets',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    /**
     * /api/profile
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function testErrorCasesOnUpdatingUserInfo(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $errorCases = [
            [
                // nickname string
                'nickname' => $this->faker->numberBetween(10000000, 999999999),
            ],
            [
                // first_name string
                'first_name' => $this->faker->numberBetween(10000000, 999999999),
            ],
            [
                // last_name string
                'last_name' => $this->faker->numberBetween(10000000, 999999999),
            ],
            [
                // city string
                'city' => $this->faker->numberBetween(10000000, 999999999),
            ],
            [
                // address string
                'address' => $this->faker->numberBetween(10000000, 999999999),
            ],
            [
                // postal_code string
                'postal_code' => $this->faker->numberBetween(10000000, 999999999),
            ],
            [
                // nickname max:255
                'nickname' => $this->faker->realTextBetween(256, 300),
            ],
            [
                // first_name max:255
                'first_name' => $this->faker->realTextBetween(256, 300),
            ],
            [
                // last_name max:255
                'last_name' => $this->faker->realTextBetween(256, 300),
            ],
            [
                // city max:255
                'city' => $this->faker->realTextBetween(256, 300),
            ],
            [
                // address max:255
                'address' => $this->faker->realTextBetween(256, 300),
            ],
            [
                // postal_code max:10
                'postal_code' => $this->faker->realTextBetween(11, 30),
            ],
            [
                // phone string max:20
                'phone' => (int)$this->faker->numerify(str_repeat('#', 25)),
            ],
            [
                // date_of_birth date
                'date_of_birth' => (int)$this->faker->numerify(str_repeat('#', 25)),
            ],
            [
                // gender boolean
                'gender' => (int)$this->faker->numerify(str_repeat('#', 25)),
            ],
            [
                // subscription_by_email boolean
                'subscription_by_email' => (int)$this->faker->numerify(str_repeat('#', 25)),
            ],
            [
                // subscription_by_sms boolean
                'subscription_by_sms' => (int)$this->faker->numerify(str_repeat('#', 25)),
            ],
        ];

        foreach ($errorCases as $errorCase) {
            $response = $this->putJson('/api/profile', $errorCase);

            $this->responseValidationFailedTest($response);
        }
    }

    /**
     * /api/profile
     *
     * @return void
     */
    public function testSuccessCaseOnUpdatingUserInfo(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $userData = User::factory()->make()->toArray();
        $response = $this->putJson('/api/profile', $userData);

        $this->successResponseTest($response);

        $this->assertDatabaseHas('users', ['nickname' => $userData['nickname']]);
    }

    private function getWalletRepository($wallet): MockInterface
    {
        return $this->mock(
            abstract: WalletRepository::class,
            mock: static function (MockInterface $mock) use ($wallet) {
                $mock->shouldReceive('list')
                    ->once()
                    ->andReturn(collect([$wallet]));
            },
        );
    }

    private function getWalletService($wallet): MockInterface
    {
        return $this->mock(
            abstract: WalletServiceInterface::class,
            mock: static function (MockInterface $mock) use ($wallet){
                $mock->shouldReceive('getBySlug')
                    ->andReturn($wallet);
            },
        );
    }
}
