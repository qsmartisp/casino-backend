<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Traits\FailedValidation;
use Tests\Traits\SuccessResponse;

/**
 * @coversDefaultClass \App\Http\Controllers\SessionController
 */
class SessionControllerTest extends TestCase
{
    use FailedValidation;
    use RefreshDatabase;
    use SuccessResponse;
    use WithFaker;

    /**
     * GET /api/sessions
     *
     * @return void
     */
    public function testCanGetListOfUserSessions(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $userData = $user->toArray();
        $userData['password'] = 'password';

        $this->postJson('/api/auth/signin', $userData)->assertStatus(200);
        $this->postJson('/api/auth/signin', $userData)->assertStatus(200);

        $response = $this->getJson('/api/sessions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'ip',
                        'browser',
                        'country' => [
                            'id',
                            'name',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                    [
                        'id',
                        'ip',
                        'browser',
                        'country' => [
                            'id',
                            'name',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    /**
     * DELETE /api/sessions
     *
     * @covers ::dropAll
     *
     * @return void
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testCanFinishAllUserSessions(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $userData = $user->toArray();
        $userData['password'] = 'password';

        $this->postJson('/api/auth/signin', $userData)->assertStatus(200);
        $this->postJson('/api/auth/signin', $userData)->assertStatus(200);

        $response = $this->getJson('/api/sessions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'ip',
                        'browser',
                        'country' => [
                            'id',
                            'name',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                    [
                        'id',
                        'ip',
                        'browser',
                        'country' => [
                            'id',
                            'name',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);

        $response = $this->deleteJson('/api/sessions');

        $response->assertStatus(204);

        $this->app->get('auth')->forgetGuards();

        $response = $this->getJson('/api/sessions');

        $response->assertStatus(401);
    }

    /**
     * DELETE /api/session
     *
     * @return void
     */
    public function testCanFinishCurrentUserSession(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $userData = $user->toArray();
        $userData['password'] = 'password';

        $this->postJson('/api/auth/signin', $userData)->assertStatus(200);
        $this->postJson('/api/auth/signin', $userData)->assertStatus(200);

        $response = $this->getJson('/api/sessions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'ip',
                        'browser',
                        'country' => [
                            'id',
                            'name',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                    [
                        'id',
                        'ip',
                        'browser',
                        'country' => [
                            'id',
                            'name',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);

        $response = $this->deleteJson('/api/session');

        $response->assertStatus(204);

        $this->postJson('/api/auth/signin', $userData)->assertStatus(200);

        $response = $this->getJson('/api/sessions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'ip',
                        'browser',
                        'country' => [
                            'id',
                            'name',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                    [
                        'id',
                        'ip',
                        'browser',
                        'country' => [
                            'id',
                            'name',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    /**
     * DELETE /api/session/{id}
     *
     * @return void
     */
    public function testCanFinishUserSessionById(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $userData = $user->toArray();
        $userData['password'] = 'password';

        $this->postJson('/api/auth/signin', $userData)->assertStatus(200);
        $this->postJson('/api/auth/signin', $userData)->assertStatus(200);

        $response = $this->getJson('/api/sessions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'ip',
                        'browser',
                        'country' => [
                            'id',
                            'name',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                    [
                        'id',
                        'ip',
                        'browser',
                        'country' => [
                            'id',
                            'name',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);

        $firstSession = $response->json('data.0');
        $secondSession = $response->json('data.1');

        $response = $this->deleteJson('/api/session/' . $firstSession['id']);

        $response->assertStatus(204);

        // todo: fix
//        $this->assertDatabaseHas('sessions', Arr::only($secondSession, ['id']));
//        $this->assertDatabaseMissing('sessions', Arr::only($firstSession, ['id']));
//        $response = $this->getJson('/api/sessions');
//
//        $response->assertStatus(200)
//            ->assertJson([
//                'data' => [
//                    $secondSession,
//                ],
//            ]);
    }
}
