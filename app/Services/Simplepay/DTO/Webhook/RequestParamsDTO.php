<?php

namespace App\Services\Simplepay\DTO\Webhook;

use App\Services\Simplepay\DTO\Caster\DataCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\DataTransferObject;

class RequestParamsDTO extends DataTransferObject
{
    public ?string $method;

    public ?int $id;

    public ?string $order;

    #[MapFrom('service_id')]
    #[MapTo('service_id')]
    public ?int $serviceId;

    public ?string $amount;

    public ?string $currency;

    public ?int $timestamp;

    #[CastWith(DataCaster::class)]
    public ?DataDto $data;

    public ?array $attributes;

    public ?string $hash;

    /**
     * @throws \JsonException
     */
    public function toString(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}
