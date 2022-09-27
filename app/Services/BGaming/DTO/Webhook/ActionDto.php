<?php

namespace App\Services\BGaming\DTO\Webhook;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\DataTransferObject;

class ActionDto extends DataTransferObject
{
    public string $action;

    public int $amount;

    #[MapFrom('action_id')]
    #[MapTo('action_id')]
    public string $actionId;

    #[MapFrom('jackpot_contribution')]
    #[MapTo('jackpot_contribution')]
    public ?float $jackpotContribution;
}
