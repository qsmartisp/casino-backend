<?php

namespace App\Services\BGaming\Responses\Webhook\Traits;

trait HasTransactions
{
    protected function transactions(): array
    {
        $transactions = [];

        foreach ((array)$this->dto->transactions as $transaction) {
            $transactions[] = [
                'action_id' => $transaction->actionId,
                'tx_id' => $transaction->txId,
                'processed_at' => $transaction->processedAt,
            ];
        }

        return $transactions;
    }
}
