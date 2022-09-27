<?php

namespace App\Services\Fundist;

use App\Services\Fundist\DTO\Webhook\WebhookParamsDTO;

class Responder
{
    protected string $hmacKey;

    public function __construct(
        protected string $hmacSecret,
    ) {
        $this->hmacKey = $this->makeHmacKey();
    }

    public function makeHmac(WebhookParamsDTO $dto): string
    {
        return hash_hmac('sha256', $dto->toString(), $this->hmacKey);
    }

    protected function makeHmacKey(): string
    {
        return hash("sha256", $this->hmacSecret, true);
    }
}
