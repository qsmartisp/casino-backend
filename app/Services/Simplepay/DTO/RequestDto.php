<?php

namespace App\Services\Simplepay\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class RequestDto extends DataTransferObject
{
    public string $url;

    public string $method;

    public RequestParamsDTO $params;
}
