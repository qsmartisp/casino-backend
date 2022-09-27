<?php

use App\Enums\Game\Aggregator\Name;
use App\Models\Aggregator;
use App\Models\Game;
use App\Models\Provider;
use App\Services\Local\Repositories\Fundist\Game\ConfigRepository;
use App\Services\Local\Repositories\GameRepository;
use App\Services\Local\Repositories\AggregatorRepository;
use App\Services\Local\Repositories\ProviderRepository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Collection;

class TransferChangesFromOldColumnsInGamesTable extends Migration
{
    protected const CHUNK_SIZE = 100;

    protected ConfigRepository $configRepository;
    protected GameRepository $gameRepository;
    protected ProviderRepository $providerRepository;
    protected AggregatorRepository $aggregatorRepository;

    public function __construct()
    {
        $this->configRepository = app(ConfigRepository::class);
        $this->gameRepository = app(GameRepository::class);
        $this->providerRepository = app(ProviderRepository::class);
        $this->aggregatorRepository = app(AggregatorRepository::class);
    }

    /**
     * Run the migrations.
     *
     * @return void
     *
     * @throws Throwable
     */
    public function up(): void
    {
        /** @var Aggregator[] $aggregators */
        $aggregators = [];
        $this->createAggregators($aggregators);

        /** @var Provider[] $providers */
        $providers = [];
        $this->createProviders($providers);

        $this->gameRepository->query()->chunk(self::CHUNK_SIZE, function (Collection $games) use ($aggregators, $providers) {
            /** @var Game $game */
            foreach ($games as $game) {
                $this->createConfig($game);
                $this->updateGameOnUp($game, $aggregators[Name::Fundist->value], $providers[$game->system_id]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     *
     * @throws Throwable
     */
    public function down(): void
    {
        $this->deleteAggregators();
        $this->deleteProviders();

        $this->gameRepository->chunk(self::CHUNK_SIZE, function (Collection $games) {
            $this->deleteConfig($games->pluck('id')->toArray());

            /** @var Game $game */
            foreach ($games as $game) {
                $this->updateGameOnDown($game);
            }
        });
    }

    private static function nameToSlugNormalize(string $name): string
    {
        // Trim whitespace
        $slug = trim($name);

        // Convert to lowercase
        $slug = mb_strtolower($slug);

        // Try replacing whitespace with a dash
        $slug = preg_replace('/\s+/u', '-', $slug) ?? $slug;

        // Try removing characters other than letters, numbers, and marks.
        $slug = preg_replace('/[^\p{L}\p{Nd}\p{Nl}\p{M}-]+/u', '', $slug) ?? $slug;

        // Trim to default string length
        return mb_substr($slug, 0, 255);
    }

    private function createConfig(Game $game): void
    {
        $this->configRepository->firstOrCreate([
            'id' => $game->id,
            'slug' => $game->page_code,
            'system_id' => $game->system_id,
            'subsystem_id' => $game->subsystem_id,
        ]);
    }

    private function deleteConfig(array $ids): void
    {
        $this->configRepository->deleteByIds($ids);
    }

    /**
     * @throws Throwable
     */
    private function updateGameOnUp(Game $game, Aggregator $aggregator, Provider $provider): void
    {
        $game->external_id = $game->id;
        $game->slug = $game->page_code;
        $game->aggregator_id = $aggregator->id;
        $game->provider_id = $provider->id;

        $game->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    private function updateGameOnDown(Game $game): void
    {
        $game->external_id = '0';
        $game->slug = '';
        $game->aggregator_id = 0;
        $game->provider_id = 0;

        $game->saveOrFail();
    }

    private function createAggregators(array &$aggregators): void
    {
        $this->getAggregators()->each(function (string $name) use (&$aggregators) {
            $aggregators[$name] = $this->aggregatorRepository->firstOrCreate([
                'name' => $name,
                'slug' => self::nameToSlugNormalize($name),
            ]);
        });
    }

    private function createProviders(array &$providers): void
    {
        $this->getProviders()->each(function (string $name, int $providerId) use (&$providers) {
            $providers[$providerId] = $this->providerRepository->firstOrCreate([
                'name' => $name,
                'slug' => self::nameToSlugNormalize($name),
            ]);
        });
    }

    private function deleteAggregators():void
    {
        $this->aggregatorRepository->deleteByNames($this->getAggregators()->values()->toArray());
    }

    private function deleteProviders():void
    {
        $this->providerRepository->deleteByNames($this->getProviders()->values()->toArray());
    }

    private function getProviders(): Collection
    {
        return collect([
            911 => 'PushGaming',
            920 => 'Thunderkick',
            944 => 'PlaynGo',
            997 => 'Microgaming',
        ]);
    }

    private function getAggregators(): Collection
    {
        return collect([
            Name::Fundist->value,
            Name::BGaming->value,
        ]);
    }
}
