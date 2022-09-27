<?php

namespace App\Services\Coinbase\DTO;

use App\Services\Coinbase\DTO\Caster\LocalPriceCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\DataTransferObject;

class RequestParamsDTO extends DataTransferObject
{
    public string $apiUrl;

    #[MapFrom('params.name')]
    public ?string $name;

    #[MapFrom('params.description')]
    public ?string $description;

    #[MapFrom('params.pricingType')]
    #[MapTo('pricing_type')]
    public ?string $pricingType;

    #[MapFrom('params.amount')]
    public ?string $amount;

    #[MapFrom('params.currency')]
    public ?string $currency;

    #[MapFrom('params.metadata')]
    public ?array $metadata; // todo

    #[MapFrom('params.redirectUrl')]
    #[MapTo('redirect_url')]
    public ?string $redirectUrl;

    #[MapFrom('params.cancelUrl')]
    #[MapTo('cancel_url')]
    public ?string $cancelUrl;

    #[CastWith(LocalPriceCaster::class)]
    public LocalPriceDto $local_price;
}
