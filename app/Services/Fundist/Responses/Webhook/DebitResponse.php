<?php

namespace App\Services\Fundist\Responses\Webhook;

use App\Services\Fundist\WebhookResponse;

class DebitResponse extends WebhookResponse
{
    public function toArray(): array
    {
        return [
            'status' => $this->dto->status,
            'hmac' => $this->dto->hmac,
            'balance' => $this->dto->balance,
            'tid' => $this->dto->tid,
        ];
    }
}
