<?php

namespace App\Services\Fundist\DTO\Webhook;

class ResponseParamsDTO extends WebhookParamsDTO
{
    public ?string $status = 'OK';

    public ?string $hmac;

    public ?string $balance;

    public ?string $tid;

    public ?string $error;

    public ?array $extra;
}
