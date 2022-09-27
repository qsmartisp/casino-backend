<?php

namespace App\Services\Fundist;

use App\Services\Fundist\DTO\Webhook\ResponseParamsDTO;

abstract class WebhookResponse
{
    public function __construct(
        protected ResponseParamsDTO $dto,
    ) {
    }

    abstract public function toArray(): array;
}
