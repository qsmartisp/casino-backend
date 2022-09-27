<?php

namespace App\Services\Coinbase\DTO\Webhook;

use App\Services\Coinbase\WebhookDtoStringify;
use Spatie\DataTransferObject\DataTransferObject;

abstract class WebhookParamsDTO extends DataTransferObject
{
    use WebhookDtoStringify;
}
