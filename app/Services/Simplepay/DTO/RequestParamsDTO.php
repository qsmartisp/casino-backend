<?php

namespace App\Services\Simplepay\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class RequestParamsDTO extends DataTransferObject
{
    public ?string $amount;

    public ?string $order;

    // TODO: fix when it will be resolved: https://github.com/spatie/data-transfer-object/issues/272
    public ?array $_merchantData;

    public ?string $service_id;

    public ?string $key;

    public ?string $timestamp;

    public ?string $hash;
}
