<?php

namespace App\Services\Fundist\DTO\Webhook;

use App\Services\Fundist\WebhookDtoStringify;
use Spatie\DataTransferObject\DataTransferObject;

abstract class WebhookParamsDTO extends DataTransferObject
{
    use WebhookDtoStringify;
}
