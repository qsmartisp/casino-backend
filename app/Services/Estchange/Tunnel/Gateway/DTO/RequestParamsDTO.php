<?php

namespace App\Services\Estchange\Tunnel\Gateway\DTO;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class RequestParamsDTO extends DataTransferObject
{
    public string $apiUrl;

    #[MapFrom('params.metadata')]
    public ?array $metadata;
}
