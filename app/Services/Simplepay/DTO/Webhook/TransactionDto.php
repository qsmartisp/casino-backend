<?php

namespace App\Services\Simplepay\DTO\Webhook;

use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\DataTransferObject;

class TransactionDto extends DataTransferObject
{
    #[MapTo('action_id')]
    public string $actionId;

    #[MapTo('tx_id')]
    public string $txId;

    #[MapTo('processed_at')]
    public string $processedAt;
}
