<?php

namespace App\Services\Simplepay\Responses\Webhook;

use App\Services\Simplepay\WebhookResponse;

class ErrorResponse extends WebhookResponse
{
    public function toArray(): array
    {
        return [
            'status' => $this->dto->status,
            'message' => $this->dto->message,
            'timestamp' => $this->dto->timestamp,
        ];
    }
}
