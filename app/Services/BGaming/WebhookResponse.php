<?php

namespace App\Services\BGaming;

use App\Services\BGaming\DTO\Webhook\ResponseParamsDTO;

abstract class WebhookResponse
{
    public function __construct(
        protected ResponseParamsDTO $dto,
    ) {
    }

    abstract public function toArray(): array;
}
