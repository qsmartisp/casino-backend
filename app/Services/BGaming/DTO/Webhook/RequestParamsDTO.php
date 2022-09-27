<?php

namespace App\Services\BGaming\DTO\Webhook;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;

class RequestParamsDTO extends DataTransferObject
{
    // Play & Rollback

    #[MapFrom('user_id')]
    #[MapTo('user_id')]
    public ?string $userId;

    public ?string $currency;

    public ?string $game;

    #[MapFrom('game_id')]
    #[MapTo('game_id')]
    public ?string $gameId;

    public ?bool $finished;

    /** @var ActionDto[] */
    #[CastWith(ArrayCaster::class, itemType: ActionDto::class)]
    public ?array $actions;



    // Freespins

    #[MapFrom('issue_id')]
    #[MapTo('issue_id')]
    public ?string $issueId;

    #[MapFrom('total_amount')]
    #[MapTo('total_amount')]
    public ?int $totalAmount;

    public ?string $status;

    /**
     * @throws \JsonException
     */
    public function toString(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}
