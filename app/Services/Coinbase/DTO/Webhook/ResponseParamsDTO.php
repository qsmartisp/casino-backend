<?php

namespace App\Services\Coinbase\DTO\Webhook;

class ResponseParamsDTO extends WebhookParamsDTO
{
    public ?string $status = 'OK';

    public ?string $error;
}
