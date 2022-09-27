<?php

namespace App\Services\BGaming\Responses\Webhook;

use App\Services\BGaming\WebhookResponse;

class BalanceResponse extends WebhookResponse
{
    public function toArray(): array
    {
        return [
            'balance' => $this->dto->balance,
        ];
    }
}
