<?php

namespace App\Services\Game\Integrations;

use App\Enums\Game\Launch\Type;
use App\Models\Game;
use App\Models\User;
use App\Services\BGaming\BGamingService;
use App\Services\BGaming\Credential;
use App\Services\BGaming\CredentialManager;
use App\Services\BGaming\Exceptions\CantRunGameException;
use App\Services\Game\Integrations\DTO\BGamingDto;
use App\Services\Game\Integrations\DTO\GameDto;
use App\Services\Game\Integration;
use App\Services\Game\Integrations\Traits\CountryByIp;
use App\Services\Game\Launch;
use GeoIp2\Database\Reader;

class BGamingIntegration implements Integration
{
    use CountryByIp;

    public function __construct(
        protected CredentialManager $credentialManager,
        protected BGamingService $bgamingService,
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
        $dto = $this->getDto(
            $ip,
            $game,
            $this->credentialManager->getCredential($user),
            $user,
        );

        $response = $this->bgamingService->createSession(
            game: $dto->game,
            currency: $dto->currency,
            locale: $dto->locale,
            userIp: $dto->ip,

            userId: $dto->externalId, // todo: fix
            externalId: $dto->externalId,
            email: $dto->email,
            firstname: $dto->firstname,
            lastname: $dto->lastname,
            nickname: $dto->nickname,
            city: $dto->city,
            dateOfBirth: $dto->dateOfBirth,
            registeredAt: $dto->registeredAt,
            gender: $dto->gender,
            country: $dto->country,
            isMobile: $dto->clientType,
            balance: $dto->balance,
        );

        if ($response->isFailed()) {
            throw new CantRunGameException($response);
        }

        return new Launch($dto, $response->asArray());
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function launchDemo(string $ip, Game $game): Launch
    {
        $dto = $this->getDto($ip, $game);

        $response = $this->bgamingService->demo(
            game: $dto->game,
            locale: $dto->locale,
            userIp: $dto->ip,
            isMobile: $dto->clientType,
        );

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
        ?Credential $credential = null,
        ?User $user = null,
    ): GameDto {
        $country = isset($user) ?
            ($this->getCurrentCountryCode($ip) ?? $user->country->code)
            : 'cy'; // todo: check

        return new BGamingDto(
            ip: $ip,
            responseType: Type::URL,
            aggregator: $game->aggregator->name,
            provider: $game->provider->name,
            gameId: $game->id,
            providerId: $game->provider_id,
            aggregatorId: $game->aggregator_id,
            locale: mb_strtolower($country), // todo: check this with business
            clientType: true, // todo: rules/source 'desktop'/'mobile'
            currency: isset($user)
                ? mb_strtoupper($user->currency->slug)
                : 'USD', // todo
            game: $game->external_id,
            balance: $user->balance ?? null,

            userId: $user->id ?? null, // todo
            firstname: $user->first_name ?? '',
            lastname: $user->last_name ?? '',
            email: $user->email ?? '',
            externalId: isset($credential) ? $credential->getLogin() : "", // todo
            nickname: isset($credential) ? $credential->getLogin() : "",
            city: $user->city ?? '',
            dateOfBirth: ($user->date_of_birth ?? now())->toDateString(), // todo
            registeredAt: ($user->created_at ?? now())->toDateString(), // todo
            gender: $user?->gender ? 'm' : 'f', // todo
            country: mb_strtoupper($country),
        );
    }
}
