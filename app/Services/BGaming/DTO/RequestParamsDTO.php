<?php

namespace App\Services\BGaming\DTO;

use App\Services\BGaming\DTO\Caster\UrlsCaster;
use App\Services\BGaming\DTO\Caster\UserCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\DataTransferObject;

class RequestParamsDTO extends DataTransferObject
{
    #[MapTo('casino_id')]
    public string $casinoId;

    #[MapFrom('params.game')]
    public ?string $game;

    #[MapFrom('params.currency')]
    public ?string $currency;

    #[MapFrom('params.locale')]
    public ?string $locale;

    #[MapFrom('params.ip')]
    public ?string $ip;

    #[MapFrom('params.clientType')]
    #[MapTo('client_type')]
    public ?string $clientType;

    #[MapFrom('params.balance')]
    public ?int $balance;

    #[CastWith(UrlsCaster::class)]
    public ?UrlsDto $urls;

    #[CastWith(UserCaster::class)]
    public ?UserDto $user;

    /**
     * @throws \JsonException
     */
    public function toString(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}
