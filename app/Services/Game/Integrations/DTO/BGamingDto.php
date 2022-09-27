<?php

namespace App\Services\Game\Integrations\DTO;

use App\Enums\Game\Launch\Type;
use Spatie\DataTransferObject\DataTransferObject;

class BGamingDto extends GameDto
{
    public ?int $userId;

    public int $gameId;

    public int $aggregatorId;

    public int $providerId;

    public string $aggregator;

    public string $provider;

    public Type $responseType;

    public ?string $ip;

    public ?string $externalId;

    public ?string $country;

    public ?string $locale;

    public ?string $clientType; // mobile/desktop

    public ?string $currency;

    public ?string $game;

    public ?string $email;

    public ?string $firstname;

    public ?string $lastname;

    public ?string $nickname;

    public ?string $city;

    public ?string $dateOfBirth;

    public ?string $registeredAt;

    public ?string $gender;

    public ?int $balance;
}
