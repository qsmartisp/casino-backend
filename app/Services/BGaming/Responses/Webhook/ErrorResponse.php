<?php

namespace App\Services\BGaming\Responses\Webhook;

use App\Services\BGaming\WebhookResponse;

class ErrorResponse extends WebhookResponse
{
    public function toArray(): array
    {
        return [
            'error' => $this->dto->error,
        ];
    }
}
