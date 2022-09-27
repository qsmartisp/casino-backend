<?php

namespace App\Services\BGaming\Responses\Webhook;

use App\Services\BGaming\WebhookResponse;

class FreespinsResponse extends WebhookResponse
{
    public function toArray(): array
    {
        return [
            'balance' => $this->dto->balance,
        ];
    }
}
