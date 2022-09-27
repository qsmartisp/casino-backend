<?php

namespace App\Services\Coinbase\Responses\Webhook;

use App\Services\Coinbase\WebhookResponse;

class ChargeResponse extends WebhookResponse
{
    public function toArray(): array
    {
        return [
            'status' => $this->dto->status,
        ];
    }
}
