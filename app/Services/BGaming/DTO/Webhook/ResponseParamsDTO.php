<?php

namespace App\Services\BGaming\DTO\Webhook;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;

class ResponseParamsDTO extends DataTransferObject
{
    public ?int $balance;

    #[MapTo('game_id')]
    public ?string $gameId;

    #[CastWith(ArrayCaster::class, itemType: TransactionDto::class)]
    public ?array $transactions;

    public ?string $error;
}
