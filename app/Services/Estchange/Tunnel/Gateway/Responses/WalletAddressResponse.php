<?php

namespace App\Services\Estchange\Tunnel\Gateway\Responses;

use App\Services\Estchange\Tunnel\Gateway\Response;

class WalletAddressResponse extends Response
{
    public function getAddress(): ?string
    {
        $response = $this->getDecodedResponse();

        return $response['address'] ?? null;
    }

    public function getUserId(): ?string
    {
        $response = $this->getDecodedResponse();

        return $response['userId'] ?? null;
    }
}
