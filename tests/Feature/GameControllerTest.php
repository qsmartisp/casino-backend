<?php

namespace Tests\Feature;

use App\Http\Controllers\GameController;
use App\Models\Aggregator;
use App\Models\Game;
use App\Models\User;
use App\Models\Wallet;
use App\Services\Fundist\FundistService;
use App\Services\Fundist\Sender;
use App\Services\Game\Integrations\FundistIntegration;
use App\Services\Game\LauncherManager;
use App\Services\Local\Repositories\Fundist\User\CredentialRepository;
use App\Services\Local\Repositories\GameRepository;
use App\Services\Local\Repositories\LaunchRepository;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\GameController
 */
class GameControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * /api/games
     *
     * @covers ::index
     *
     * @return void
     */
    public function testCanGetListOfGames(): void
    {
        Game::factory()->create();

        $response = $this->getJson('/api/games');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'slug',
                        'name',
                        'has_demo',
                        'image',
                        'provider',
                    ],
                ],
            ]);
    }

    /**
     * /api/games?page=2
     *
     * @covers ::index
     *
     * @return void
     */
    public function testCanGetSecondPageOfListOfGames(): void
    {
        Game::factory(19)->create([
            'status' => 'enabled',
        ]);

        $response = $this->getJson('/api/games?page=2');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'slug',
                        'name',
                        'has_demo',
                        'image',
                        'provider',
                    ],
                ],
            ]);
    }

    /**
     * /api/games?page=2
     *
     * @covers ::run
     *
     * @return void
     */
    public function testCanRunGame(): void
    {
        // todo: Fix dependencies problems
        $this->markTestSkipped();
        $this->app
            ->when(Sender::class)
            ->needs(ClientInterface::class)
            ->give(fn() => $this->getClient());
        $this->app
            ->when(Sender::class)
            ->needs(LoggerInterface::class)
            ->give(fn() => $this->getLogger());

        /** @var Game $game */
        $game = Game::factory()->create([
            'aggregator_id' => Aggregator::factory()->create([
                'name' => 'Fundist',
            ])->id,
        ]);

        /** @var User $user */
        $user = User::factory()->create();

        Wallet::factory()->create([
            'holder_id' => $user->id,
            'slug' => 'usd',
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/game/' . $game->slug);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'content',
                ],
            ]);
    }

    private function getClient(): MockInterface
    {
        return $this->mock(
            abstract: ClientInterface::class,
            mock: static function (MockInterface $mock) {
                $mock->shouldReceive('request')
                    ->withAnyArgs()
                    ->once()
                    ->andReturn(new Response(body: "1,<div></div>"));
            },
        );
    }

    private function getLogger(): MockInterface
    {
        return $this->mock(
            abstract: LoggerInterface::class,
            mock: static function (MockInterface $mock) {
                $mock
                    //->shouldReceive('debug')
                    ->shouldReceive('error')
                    ->once();
            },
        );
    }

    private function getGameRepository(): MockInterface
    {
        return $this->mock(GameRepository::class);
    }

    private function getFundistService(): MockInterface
    {
        return $this->mock(FundistService::class);
    }

    private function getFundistIntegration(): MockInterface
    {
        return $this->mock(FundistIntegration::class);
    }

    private function getCredentialRepository(): MockInterface
    {
        return $this->mock(CredentialRepository::class);
    }

    private function getLaunchRepository(): MockInterface
    {
        return $this->mock(LaunchRepository::class);
    }

    private function getLauncherManager(): MockInterface
    {
        return $this->mock(LauncherManager::class);
    }
}
