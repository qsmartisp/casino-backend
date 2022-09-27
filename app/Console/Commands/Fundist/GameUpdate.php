<?php

namespace App\Console\Commands\Fundist;

use App\Enums\Game\Aggregator\Name;
use App\Enums\Game\Status;
use App\Models\Aggregator;
use App\Models\File;
use App\Models\Game;
use App\Models\Provider;
use App\Services\FileService;
use App\Services\Fundist\FundistService;
use App\Services\Game\Help\Normalizers\SlugNormalizer;
use App\Services\Game\Traits\GameUpdater;
use App\Services\Game\Traits\HaveImage;
use App\Services\Local\Repositories\AggregatorRepository;
use App\Services\Local\Repositories\Fundist\Game\ConfigRepository;
use App\Services\Local\Repositories\GameRepository;
use App\Services\Local\Repositories\ProviderRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use JsonException;

class GameUpdate extends Command
{
    use HaveImage;
    use GameUpdater;

    protected $signature = 'fundist:games:update';

    protected $description = 'Update games from fundist';

    private static ConfigRepository $configRepository;
    private static array $providers;
    private static array $countriesRestrictions;

    public function __construct(
        FileService $fileRepository,
        AggregatorRepository $aggregatorRepository,
        ProviderRepository $providerRepository,
        GameRepository $gameRepository,
        ConfigRepository $configRepository,
        protected SlugNormalizer $slugNormalizer,
    ) {
        parent::__construct();

        static::$fileRepository = $fileRepository;
        static::$aggregatorRepository = $aggregatorRepository;
        static::$providerRepository = $providerRepository;
        static::$gameRepository = $gameRepository;
        static::$configRepository = $configRepository;
    }

    /**
     * @throws JsonException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function handle(FundistService $api): int
    {
        static::$out = $this->getOutput();

        $result = $api->getGameFullList();

        if ($result->isOk() === false) {
            self::$out->newLine();
            self::$out->error("Fundist answered: {$result->toString()}");

            return 1;
        }

        self::$providers = $result->merchants();
        self::$countriesRestrictions = $result->countriesRestrictions();

        $this->gameUpdateProcess(
            collect($result->games()),
            'ID',
            Name::Fundist,
        );

        return 0;
    }

    protected function createGame(
        Aggregator $aggregator,
        string $externalKey,
        array $gameInfo,
    ): ?Game {
        $id = $gameInfo[$externalKey];

        if (is_null($id)) {
            self::$out->newLine();
            self::$out->warning("Game info from Fundist has not ID"); // todo: check sentence

            return null;
        }

        $provider = $this->createFundistProvider($aggregator, $gameInfo);

        $url = $gameInfo['Url'];
        $mobileUrl = $gameInfo['MobileUrl'];
        $name = $gameInfo['Name']['en'];
        $hasDemo = $gameInfo['hasDemo'];

        $game = self::$gameRepository->store([
            'slug' => $this->makeSlug($id, $name, $provider->slug),
            'external_id' => $id,
            'name' => $name,

            'is_desktop' => !empty($url),
            'is_mobile' => !empty($mobileUrl),
            'has_demo' => !empty($hasDemo),

            'aggregator_id' => $aggregator->id,
            'provider_id' => $provider->id,
        ]);

        // Image
        $imageName = $gameInfo['ImageFullPath'];

        if ($this->setImage($game, $imageName) === false) {
            self::$out->newLine();
            self::$out->warning("Cant set image (url=$imageName) for game (id=$game->id)");
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

        $provider = $this->createFundistProvider($aggregator, $gameInfo);

        self::$gameRepository->update($game, [
            'name' => $gameInfo['Name']['en'],

            'is_mobile' => !empty($gameInfo['MobileUrl']),
            'is_desktop' => !empty($gameInfo['Url']),
            'has_demo' => !empty($gameInfo['hasDemo']),

            'aggregator_id' => $aggregator->id,
            'provider_id' => $provider->id,

            'status' => Status::Enabled->value,
        ]);

        // Image
        /** @var File $oldImage */
        $oldImage = $game->images()->first();
        $imageName = $gameInfo['ImageFullPath'];
        $setImage = $this->setImage($game, $imageName, $oldImage);

        if ($setImage === false) {
            self::$out->newLine();
            self::$out->warning("Cant set image (url=$imageName) for game (id=$game->id)");
        } elseif ($setImage === true && !is_null($oldImage)) {
            $imagePathsForDestroy->push($oldImage->path);
            $oldImage->delete();
        }

        return $game;
    }

    private function createFundistProvider(Aggregator $aggregator, array $gameInfo): Provider
    {
        $provider = self::$providers[$gameInfo['MerchantID']] ?? self::$providers[$gameInfo['SubMerchantID']];

        return $this->createProvider($aggregator, $provider['Alias']);
    }

    protected function createGameConfig(Game $game, array $gameInfo): void
    {
        self::$configRepository->store([
            'slug' => $game->slug,

            'id' => $game->external_id,

            'system_id' => $gameInfo['MerchantID'],
            'subsystem_id' => $gameInfo['SubMerchantID'],
            'page_code' => $gameInfo['PageCode'],
            'mobile_page_code' => $gameInfo['MobilePageCode'],
        ]);
    }

    protected function updateGameConfig(Game $game, array $gameInfo): void
    {
        self::$configRepository->update($game->config, [
            'system_id' => $gameInfo['MerchantID'],
            'subsystem_id' => $gameInfo['SubMerchantID'],
            'page_code' => $gameInfo['PageCode'],
            'mobile_page_code' => $gameInfo['MobilePageCode'],
        ]);
    }

    protected function makeSlug(
        string $id,
        string $name,
        string $provider,
        string $delim = ':',
    ): string {
        return $this->slugNormalizer->normalize($name)
            . $delim . $provider
            . $delim . $id;
    }
}
