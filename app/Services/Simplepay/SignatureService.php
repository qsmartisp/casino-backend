<?php

namespace App\Services\Simplepay;

class SignatureService
{
    public function __construct(
        private string $apiSecretKey,
        private string $signingSecretKey,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function signatureForApi(string $body): string
    {
        return $this->signature($body, $this->apiSecretKey);
    }

    /**
     * @throws \Exception
     */
    public function signatureForWebhook(string $body): string
    {
        return $this->signature($body, $this->signingSecretKey);
    }

    /**
     * @throws \Exception
     */
    protected function signature(string $body, string $key): string
    {
        return hash_hmac('sha1', $body, $key);
    }

    public function prepareData(array $params, ?string $apiMethod = null): string
    {
        return ($apiMethod ? ($apiMethod . '?') : '')
            . http_build_query($params);
    }
}
