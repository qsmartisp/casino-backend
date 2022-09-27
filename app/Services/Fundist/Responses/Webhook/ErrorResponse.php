<?php

namespace App\Services\Fundist\Responses\Webhook;

use App\Services\Fundist\WebhookResponse;

class ErrorResponse extends WebhookResponse
{
    public function toArray(): array
    {
        return [
            'error' => $this->dto->error,
            'hmac' => $this->dto->hmac,
        ];
    }
}
