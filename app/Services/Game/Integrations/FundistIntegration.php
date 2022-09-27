<?php

namespace App\Services\Game\Integrations;

use App\Enums\Game\Launch\Type;
use App\Models\Game;
use App\Models\User;
use App\Services\Fundist\Credential;
use App\Services\Fundist\CredentialManager;
use App\Services\Fundist\Exceptions\CantRunGameException;
use App\Services\Fundist\FundistService;
use App\Services\Fundist\Responses\UserAuthHTMLResponse;
use App\Services\Game\Integrations\DTO\FundistDto;
use App\Services\Game\Integrations\DTO\GameDto;
use App\Services\Game\Integration;
use App\Services\Game\Integrations\Traits\CountryByIp;
use App\Services\Game\Launch;
use GeoIp2\Database\Reader;

class FundistIntegration implements Integration
{
    use CountryByIp;

    public function __construct(
        protected CredentialManager $credentialManager,
        protected FundistService $fundistService,
        protected Reader $reader,
    ) {
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function launch(string $ip, Game $game, User $user): Launch
    {
        return $this->run($ip, $game, $this->credentialManager->getCredential($ip, $user), $user);
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function launchDemo(string $ip, Game $game): Launch
    {
        return $this->run($ip, $game, $this->credentialManager->getDemoCredential());
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    private function run(
        string $ip,
        Game $game,
        Credential $credential,
        ?User $user = null,
    ): Launch {
        $dto = $this->getDto($ip, $game, $credential, $user);
        $response = $this->makeRequest($dto);

        if ($response->isFailed()) {
            throw new CantRunGameException($response);
        }

        return new Launch($dto, $response->asArray());
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    private function getDto(
        string $ip,
        Game $game,
        Credential $credential,
        ?User $user = null,
    ): GameDto {
        return new FundistDto(
            ip: $ip,

            responseType: Type::HTML,

            aggregator: $game->aggregator->name,
            provider: $game->provider->name,

            externalParams: "$game->id|$game->name",

            // todo: fix on mobile support
            pageCode: $game->config->page_code ?? $game->config->mobile_page_code,
            systemId: $game->config->system_id,

            country: isset($user) ?
                ($this->getCurrentCountryCode($ip) ?? $user->country->code)
                : null,

            login: $credential->getLogin(),
            password: $credential->getPassword(),

            userId: $user->id ?? 0,
            gameId: $game->id,
            providerId: $game->provider_id,
            aggregatorId: $game->aggregator_id,

            demo: isset($user) ? null : 1,
        );
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    private function makeRequest(GameDto $dto): UserAuthHTMLResponse
    {
        return $this->fundistService->runGame(
            systemId: $dto->systemId,
            login: $dto->login,
            password: $dto->password,
            page: $dto->pageCode,
            userIp: $dto->ip,
            demo: $dto->demo,
            gameExtra: $dto->externalParams,
            country: $dto->country,
        );
    }
}
