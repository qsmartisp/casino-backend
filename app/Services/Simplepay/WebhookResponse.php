<?php

namespace App\Services\Simplepay;

use App\Services\Simplepay\DTO\Webhook\ResponseParamsDTO;

abstract class WebhookResponse
{
    public function __construct(
        protected ResponseParamsDTO $dto,
    ) {
    }

    abstract public function toArray(): array;
}
