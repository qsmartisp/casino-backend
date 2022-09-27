<?php

namespace App\Http\Controllers;

use App\Http\Requests\Game\GamesRequest;
use App\Http\Resources\Game\GameResource;
use App\Http\Resources\Game\LaunchResource;
use App\Models\Launch;
use App\Services\Game\LauncherManager;
use App\Services\Local\Repositories\Contracts\GameRepository;
use App\Services\Local\Repositories\LaunchRepository;
use Illuminate\Http\Request;
use JsonSerializable;

class GameController extends Controller
{
    public function __construct(
        protected GameRepository $repository,
    ) {
    }

    public function index(GamesRequest $request): JsonSerializable
    {
        return GameResource::collection(
            $this->repository->filterGamesWithPagination(
                $request->input('per_page', config('games.index_pagination', 18)),
                $request->input('page', 1),
                $request->name,
                $request->provider_id,
                $request->tag,
                $request->provider_ids,
            ),
        );
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function run(
        string $slug,
        Request $request,
        LauncherManager $launcherManager,
        LaunchRepository $launchRepository,
    ): LaunchResource {
        $game = $this->repository->findBySlug($slug);
        $gameLaunch = $launcherManager
            ->launcher($game)
            ->launch($request->ip(), $game, $request->user());

        $launch = $launchRepository->store([
            'user_id' => $gameLaunch->dto->userId,
            'game_id' => $gameLaunch->dto->gameId,
            'aggregator_id' => $gameLaunch->dto->aggregatorId,
            'provider_id' => $gameLaunch->dto->providerId,

            'ip' => $gameLaunch->dto->ip,
            'type' => $gameLaunch->dto->responseType->value,

            'data' => $gameLaunch->response,
        ]);

        return LaunchResource::make($launch);
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function runDemo(
        string $slug,
        Request $request,
        LauncherManager $launcherManager,
    ): LaunchResource {
        $game = $this->repository->findBySlug($slug);
        $gameLaunch = $launcherManager
            ->launcher($game)
            ->launchDemo($request->ip(), $game);

        $launch = new Launch();
        $launch->type = $gameLaunch->dto->responseType->value;
        $launch->data = $gameLaunch->response;

        return LaunchResource::make($launch);
    }
}
