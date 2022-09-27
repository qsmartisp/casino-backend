<?php

namespace App\Services\Estchange\Tunnel\Gateway;

use App\Services\Estchange\Tunnel\Gateway\DTO\Webhook\ResponseParamsDTO;

abstract class WebhookResponse
{
    public function __construct(
        protected ResponseParamsDTO $dto,
    ) {
    }

    abstract public function toArray(): array;
}
