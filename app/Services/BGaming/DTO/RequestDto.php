<?php

namespace App\Services\BGaming\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class RequestDto extends DataTransferObject
{
    public string $url;

    public RequestParamsDTO $params;
}
