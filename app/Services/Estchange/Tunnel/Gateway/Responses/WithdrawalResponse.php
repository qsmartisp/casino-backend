<?php

namespace App\Services\Estchange\Tunnel\Gateway\Responses;

use App\Services\Estchange\Tunnel\Gateway\Response;

class WithdrawalResponse extends Response
{
    public function getStatus(): ?int
    {
        $response = $this->getDecodedResponse();

        return $response['status'] ?? null;
    }

    public function isSuccess(): bool
    {
        return $this->getStatus() === 1;
    }

    public function isNotSuccess(): bool
    {
        return $this->getStatus() !== 1;
    }

    public function getWithdrawalId(): ?string
    {
        $response = $this->getDecodedResponse();

        return $response['withdrawalId'] ?? null;
    }

    public function getOutgoingRequestId(): ?string
    {
        $response = $this->getDecodedResponse();

        return $response['outgoingRequestId'] ?? null;
    }
}
