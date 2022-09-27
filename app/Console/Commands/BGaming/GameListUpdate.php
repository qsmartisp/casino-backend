<?php

namespace App\Console\Commands\BGaming;

use App\Enums\Game\Aggregator\Name;
use App\Enums\Game\Status;
use App\Models\Aggregator;
use App\Models\File;
use App\Models\Game;
use App\Services\FileService;
use App\Services\Game\Traits\GameUpdater;
use App\Services\Game\Traits\HaveImage;
use App\Services\Local\Repositories\AggregatorRepository;
use App\Services\Local\Repositories\BGaming\Game\ConfigRepository;
use App\Services\Local\Repositories\GameRepository;
use App\Services\Local\Repositories\ProviderRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Yaml\Yaml;

class GameListUpdate extends Command
{
    use HaveImage;
    use GameUpdater;

    protected $signature = 'bgaming:games:update
                            {path=/var/www/storage/app/bgaming/letterm-int.yml : Path to YAML config file (located locally)}
                            {--force : Ignore default exception}
    ';

    protected $description = 'Update games from bgaming';

    private static ConfigRepository $configRepository;

    public function __construct(
        FileService $fileRepository,
        AggregatorRepository $aggregatorRepository,
        ProviderRepository $providerRepository,
        GameRepository $gameRepository,
        ConfigRepository $configRepository,
    ) {
        parent::__construct();

        static::$fileRepository = $fileRepository;
        static::$aggregatorRepository = $aggregatorRepository;
        static::$providerRepository = $providerRepository;
        static::$gameRepository = $gameRepository;
        static::$configRepository = $configRepository;
    }

    /**
     * @throws \JsonException
     */
    public function handle(): int
    {
        static::$out = $this->getOutput();

        $this->gameUpdateProcess(
            $this->parseYAML($this->argument('path')),
            'identifier',
            Name::BGaming,
        );

        return 0;
    }

    private function parseYAML(string $path): Collection
    {
        return collect(Yaml::parse(file_get_contents($path)));
    }

    private function setMobileAndDesktop(
        array $devices,
        bool &$isDesktop,
        bool &$isMobile
    ): void {
        collect($devices)->filter(function ($item) use (&$isDesktop, &$isMobile) {
            if ($item === 'mobile') {
                $isMobile = true;
            }

            if ($item === 'desktop') {
                $isDesktop = true;
            }

            return true;
        });
    }

    private function getImageUrl(string $slug): string
    {
        return "https://cdn.softswiss.net/i/s3/softswiss/$slug.png";
    }

    protected function createGame(
        Aggregator $aggregator,
        string $externalKey,
        array $gameInfo,
    ): ?Game {
        $isDesktop = false;
        $isMobile = false;

        $this->setMobileAndDesktop($gameInfo['devices'], $isDesktop, $isMobile);

        $provider = $this->createProvider($aggregator, $gameInfo['provider']);

        $game = self::$gameRepository->store([
            'slug' => $gameInfo[$externalKey],
            'external_id' => $gameInfo[$externalKey],

            'name' => $gameInfo['title'],

            'is_mobile' => $isMobile,
            'is_desktop' => $isDesktop,
            'has_demo' => 1,

            'aggregator_id' => $aggregator->id,
            'provider_id' => $provider->id,
        ]);


        // Image
        $imageName = $this->getImageUrl($gameInfo[$externalKey]);

        if ($this->setImage($game, $imageName) === false) {
            self::$out->newLine();
            self::$out->warning("Cant set image (url=$imageName) for game (id=$game->id). Error 404. Skipped");
        }

        return $game;
    }

    protected function updateGame(
        Aggregator $aggregator,
        string $externalKey,
        Collection $imagePathsForDestroy,
        Game $game,
        ?array $gameInfo = null,
    ): Game {
        if (is_null($gameInfo)) {
            $game->status = Status::Disabled->value;
            $game->save();

            return $game;
        }

        $isDesktop = false;
        $isMobile = false;

        $this->setMobileAndDesktop($gameInfo['devices'], $isDesktop, $isMobile);

        $provider = $this->createProvider($aggregator, $gameInfo['provider']);

        self::$gameRepository->update($game, [
            'name' => $gameInfo['title'],

            'is_mobile' => $isMobile,
            'is_desktop' => $isDesktop,
            'has_demo' => 1,

            'aggregator_id' => $aggregator->id,
            'provider_id' => $provider->id,

            'status' => Status::Enabled->value,
        ]);

        // Image
        /** @var File $oldImage */
        $oldImage = $game->images()->first();
        $imageName = $this->getImageUrl($gameInfo[$externalKey]);
        $setImage = $this->setImage($game, $imageName, $oldImage);

        if ($setImage === false) {
            self::$out->newLine();
            self::$out->warning("Cant set image (url=$imageName) for game (id=$game->id). Error 404. Skipped");
        } elseif ($setImage === true && !is_null($oldImage)) {
            $imagePathsForDestroy->push($oldImage->path);
            $oldImage->delete();
        }

        return $game;
    }

    protected function createGameConfig(Game $game, array $gameInfo): void
    {
        self::$configRepository->store([
            'slug' => $game->slug,

            'category' => $gameInfo['category'],
            'feature_group' => $gameInfo['feature_group'],
        ]);
    }

    protected function updateGameConfig(Game $game, array $gameInfo): void
    {
        self::$configRepository->update($game->config, [
            'category' => $gameInfo['category'],
            'feature_group' => $gameInfo['feature_group'],
        ]);
    }
}
