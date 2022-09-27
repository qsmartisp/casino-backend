<?php

namespace App\Services\Estchange\Tunnel\Gateway\DTO\Webhook;

class ResponseParamsDTO extends WebhookParamsDTO
{
    public ?string $status = 'OK';

    public ?string $error;
}
