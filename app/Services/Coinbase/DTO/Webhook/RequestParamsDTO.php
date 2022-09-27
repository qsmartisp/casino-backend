<?php

namespace App\Services\Coinbase\DTO\Webhook;

use Spatie\DataTransferObject\Attributes\MapFrom;

class RequestParamsDTO extends WebhookParamsDTO
{
    public string $signature;

    #[MapFrom('request.id')]
    public string $id;

    #[MapFrom('request.event.id')]
    public string $eventId;

    #[MapFrom('request.event.resource')]
    public string $eventResource;

    #[MapFrom('request.event.type')]
    public string $eventType;

    #[MapFrom('request.event.data.code')]
    public string $eventCode;

    #[MapFrom('request.event.data.hosted_url')]
    public string $url;

    #[MapFrom('request.event.data.pricing.local.amount')]
    public string $amount;

    #[MapFrom('request.event.data.pricing.local.currency')]
    public string $currency;

    #[MapFrom('request.event.data.metadata.user_id')]
    public ?int $userId;
}
