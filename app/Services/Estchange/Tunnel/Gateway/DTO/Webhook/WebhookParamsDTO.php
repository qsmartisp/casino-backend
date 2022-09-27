<?php

namespace App\Services\Estchange\Tunnel\Gateway\DTO\Webhook;

use App\Services\Estchange\Tunnel\Gateway\WebhookDtoStringify;
use Spatie\DataTransferObject\DataTransferObject;

abstract class WebhookParamsDTO extends DataTransferObject
{
    use WebhookDtoStringify;
}
