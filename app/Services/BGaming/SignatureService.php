<?php

namespace App\Services\BGaming;

class SignatureService
{
    public function __construct(
        protected string $authToken,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function signature(string $body): string
    {
        return hash_hmac('sha256', $body, $this->authToken);
    }
}
