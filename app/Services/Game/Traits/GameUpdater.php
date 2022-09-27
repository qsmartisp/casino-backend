<?php

namespace App\Services\Game\Traits;

use App\Enums\Game\Aggregator\Name;
use App\Exceptions\CantDeleteGameImages;
use App\Models\Aggregator;
use App\Models\Provider;
use App\Services\Local\Repositories\AggregatorRepository;
use App\Services\Local\Repositories\GameRepository;
use App\Services\Local\Repositories\ProviderRepository;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Helper\ProgressBar;

trait GameUpdater
{
    protected static AggregatorRepository $aggregatorRepository;
    protected static ProviderRepository $providerRepository;
    protected static GameRepository $gameRepository;
    protected static ProgressBar $bar;
    protected static OutputStyle $out;

    /**
     * @throws \JsonException
     */
    private function gameUpdateProcess(
        Collection $newGames,
        string $externalKey,
        Name $aggregatorName,
    ): void {
        $aggregator = $this->createAggregator($aggregatorName);
        $oldGames = self::$gameRepository->getByAggregator($aggregator);

        // UPDATE
        $gamesToUpdate = $oldGames->pluck('external_id');

        if ($gamesToUpdate->count() > 0) {
            self::$bar = self::$out->createProgressBar();
            self::$out->newLine();
            self::$out->title("Update old games and their images [{$gamesToUpdate->count()}]");
            self::$bar->setMaxSteps($gamesToUpdate->count());

            $games = $this->updateGames(
                $aggregator,
                $newGames,
                $oldGames,
                $gamesToUpdate,
                $externalKey,
            );

            self::$bar->finish();
            self::$out->newLine();
            self::$out->info('IDs of updated games: ' . json_encode($games->pluck('id')->toArray(), JSON_THROW_ON_ERROR));
        }


        // CREATE
        $gamesToCreate = $newGames->pluck($externalKey)->diff($gamesToUpdate->toArray());

        if ($gamesToCreate->count() > 0) {
            self::$bar = self::$out->createProgressBar();
            self::$out->newLine();
            self::$out->title("Create new games and their images [" . $gamesToCreate->count() . "]");
            self::$bar->setMaxSteps($gamesToCreate->count());

            $games = $this->createGames(
                $aggregator,
                $newGames,
                $gamesToCreate,
                $externalKey,
            );

            self::$bar->finish();
            self::$out->newLine();
            self::$out->info('IDs of created games: ' . json_encode($games->pluck('id')->toArray(), JSON_THROW_ON_ERROR));
        }
    }

    protected function createGames(
        Aggregator $aggregator,
        Collection $gamesInfo,
        Collection $gamesToCreate,
        string $externalKey,
    ): Collection {
        $games = collect();

        foreach ($gamesToCreate->toArray() as $gameToCreate) {
            $gameInfo = $gamesInfo->first(fn($item) => $item[$externalKey] === $gameToCreate);
            $game = $this->createGame(
                $aggregator,
                $externalKey,
                $gameInfo,
            );

            if (!is_null($game)) {
                $games->push($game);

                $this->createGameConfig($game, $gameInfo);
            }

            self::$bar->advance();
        }

        return $games;
    }

    protected function updateGames(
        Aggregator $aggregator,
        Collection $gamesInfo,
        Collection $localGames,
        Collection $gamesToUpdate,
        string $externalKey,
    ): Collection {
        $games = collect();
        $imagePathsForDestroy = collect();

        foreach ($gamesToUpdate->toArray() as $gameToUpdate) {
            $gameInfo = $gamesInfo->first(fn($item) => $item[$externalKey] === $gameToUpdate);
            $game = $this->updateGame(
                $aggregator,
                $externalKey,
                $imagePathsForDestroy,
                $localGames->first(fn($item) => $item['external_id'] === $gameToUpdate),
                $gameInfo,
            );

            $games->push($game);

            if (!is_null($gameInfo)) {
                $this->updateGameConfig($game, $gameInfo);
            }

            self::$bar->advance();
        }

        if (false === self::$fileRepository->deleteByGamePaths($imagePathsForDestroy)) {
            throw new CantDeleteGameImages("Can't delete game images from storage");
        }

        return self::$gameRepository->getByAggregator($aggregator);
    }

    protected function createAggregator(Name $aggregatorName): Aggregator
    {
        return self::$aggregatorRepository->firstOrCreate([
            'slug' => $aggregatorName->value,
        ], [
            'slug' => $aggregatorName->value,
            'name' => $aggregatorName->value,
        ]);
    }

    protected function createProvider(Aggregator $aggregator, string $slug): Provider
    {
        $provider = self::$providerRepository->getBySlug($slug);

        if (is_null($provider)) {
            $provider = self::$providerRepository->store([
                'slug' => mb_strtolower($slug),
                'name' => $slug,
            ]);

            $aggregator->providers()->attach($provider->id);
        }

        return $provider;
    }
}