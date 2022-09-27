<?php

namespace App\Services\BGaming\Responses\Webhook;

use App\Services\BGaming\Responses\Webhook\Traits\HasTransactions;
use App\Services\BGaming\WebhookResponse;

class RollbackResponse extends WebhookResponse
{
    use HasTransactions;

    public function toArray(): array
    {
        return [
            'balance' => $this->dto->balance,
            'game_id' => $this->dto->gameId,
            'transactions' => $this->transactions(),
        ];
    }
}
