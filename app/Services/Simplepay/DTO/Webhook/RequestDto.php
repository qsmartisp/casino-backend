<?php

namespace App\Services\Simplepay\DTO\Webhook;

use Spatie\DataTransferObject\DataTransferObject;

class RequestDto extends DataTransferObject
{
    public string $signature;

    public RequestParamsDTO $params;
}
