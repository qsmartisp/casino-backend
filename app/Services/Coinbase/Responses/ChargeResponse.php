<?php

namespace App\Services\Coinbase\Responses;

use App\Services\Coinbase\Response;

class ChargeResponse extends Response
{
    public function getUrl(): ?string
    {
        $response = $this->getDecodedResponse();

        return $response['data']['hosted_url'] ?? null;
    }

    public function getCode(): ?string
    {
        $response = $this->getDecodedResponse();

        return $response['data']['code'] ?? null;
    }
}
