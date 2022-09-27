<?php

namespace App\Services\Game\Integrations\DTO;

use App\Enums\Game\Launch\Type;
use Spatie\DataTransferObject\DataTransferObject;

class FundistDto extends GameDto
{
    public ?int $userId;

    public int $gameId;

    public int $aggregatorId;

    public int $providerId;

    public string $aggregator;

    public string $provider;

    public Type $responseType;

    public ?string $login;

    public ?string $password;

    public ?string $ip;

    public ?string $systemId;

    public ?string $pageCode;

    public ?string $externalId;

    public ?string $externalParams;

    public ?string $country;

    public ?bool $demo;
}
