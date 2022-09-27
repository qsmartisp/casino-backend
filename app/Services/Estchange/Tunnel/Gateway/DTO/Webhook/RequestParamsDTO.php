<?php

namespace App\Services\Estchange\Tunnel\Gateway\DTO\Webhook;

use App\Services\Estchange\Tunnel\Gateway\DTO\CarbonDto;
use App\Services\Estchange\Tunnel\Gateway\DTO\Caster\CarbonCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;

class RequestParamsDTO extends WebhookParamsDTO
{
    public string $signature;

    #[MapFrom('request.transactionId')]
    public ?string $transactionId;

    #[MapFrom('request.withdrawalId')]
    public ?string $withdrawalId;

    #[MapFrom('request.hash')]
    public ?string $hash;

    #[MapFrom('request.type')]
    public string $type;

    #[MapFrom('request.address')]
    public string $address;

    #[MapFrom('request.amountType')]
    public ?string $amountType;

    #[MapFrom('request.amount')]
    public ?float $amount;

    #[MapFrom('request.debited')]
    public ?float $debited;

    #[MapFrom('request.sent')]
    public ?float $sent;

    #[MapFrom('request.revenueAmount')]
    public ?float $revenueAmount;

    #[MapFrom('request.revenueCurrency')]
    public ?string $revenueCurrency;

    #[MapFrom('request.status')]
    public ?int $status;

    #[MapFrom('request.userId')]
    public ?string $userId;

    #[MapFrom('request.fee')]
    public ?float $fee;

    #[MapFrom('request.coin')]
    public ?string $coin;

    #[MapFrom('request.pare')]
    public ?string $pare;

    #[MapFrom('request.description')]
    public ?string $description;

    #[MapFrom('request.requestId')]
    public ?int $requestId;

    #[MapFrom('request.txId')]
    public ?string $txId;

    #[MapFrom('request.reference')]
    public ?string $reference;

    #[MapFrom('request.webhook_slug')]
    public ?string $webhookSlug;

    #[MapFrom('request.createdAt')]
    #[CastWith(CarbonCaster::class)]
    public ?CarbonDto $createdAt;

    #[MapFrom('request.updatedAt')]
    #[CastWith(CarbonCaster::class)]
    public ?CarbonDto $updatedAt;
}
