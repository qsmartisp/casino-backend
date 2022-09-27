<?php

namespace App\Services\BGaming\DTO\Webhook;

use Spatie\DataTransferObject\DataTransferObject;

class RequestDto extends DataTransferObject
{
    public string $signature;

    public RequestParamsDTO $params;
}
