<?php

namespace App\Services\Estchange\Tunnel\Gateway\Responses\Webhook;

use App\Services\Estchange\Tunnel\Gateway\WebhookResponse;

class ChargeResponse extends WebhookResponse
{
    public function toArray(): array
    {
        return [
            'status' => $this->dto->status,
        ];
    }
}
