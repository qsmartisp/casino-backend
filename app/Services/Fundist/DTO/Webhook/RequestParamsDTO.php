<?php

namespace App\Services\Fundist\DTO\Webhook;

use Spatie\DataTransferObject\Attributes\MapFrom;

class RequestParamsDTO extends WebhookParamsDTO
{
    #[MapFrom('request.hmac')]
    public string $hmac;

    #[MapFrom('request.type')]
    public string $type;

    #[MapFrom('request.balance')]
    public ?float $balance;

    #[MapFrom('request.userid')]
    public ?string $userid;

    #[MapFrom('request.currency')]
    public ?string $currency;

    #[MapFrom('request.tid')]
    public ?string $tid;

    #[MapFrom('request.amount')]
    public ?string $amount;

    #[MapFrom('request.i_extparam')]
    public ?string $i_extparam;

    #[MapFrom('request.i_gamedesc')]
    public ?string $i_gamedesc;

    #[MapFrom('request.i_gameid')]
    public ?string $i_gameid;

    #[MapFrom('request.i_actionid')]
    public ?string $i_actionid;

    #[MapFrom('request.i_rollback')]
    public ?string $i_rollback;

    #[MapFrom('request.i_reference_actionid')]
    public ?string $i_reference_actionid;

    #[MapFrom('request.game_extra')]
    public ?string $game_extra;

    #[MapFrom('request.subtype')]
    public ?string $subtype;

    #[MapFrom('request.round_start')]
    public ?bool $round_start;

    #[MapFrom('request.round_ended')]
    public ?bool $round_ended;

    #[MapFrom('request.jackpot_win')]
    public ?int $jackpot_win;
}

