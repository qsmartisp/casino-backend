<?php

namespace App\Services\Game\Integrations\DTO;

use App\Enums\Game\Launch\Type;
use Spatie\DataTransferObject\DataTransferObject;

class GameDto extends DataTransferObject
{
    public ?int $userId;

    public int $gameId;

    public int $aggregatorId;

    public int $providerId;

    public string $aggregator;

    public string $provider;

    public Type $responseType;
}
