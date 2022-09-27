<?php

namespace App\Services\Estchange\Tunnel\Gateway\Responses;

use App\Services\Estchange\Tunnel\Gateway\Response;

class CurrencyRateResponse extends Response
{
    public function getCurrency(): ?string
    {
        $response = $this->getDecodedResponse();

        return $response['currency'] ?? null;
    }

    public function getAsk(): ?string
    {
        $response = $this->getDecodedResponse();

        return $response['ask'] ?? null;
    }

    public function getBid(): ?string
    {
        $response = $this->getDecodedResponse();

        return $response['bid'] ?? null;
    }
}
