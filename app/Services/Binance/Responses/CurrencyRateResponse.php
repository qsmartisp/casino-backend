<?php

namespace App\Services\Binance\Responses;

use App\Services\Binance\Response;

class CurrencyRateResponse extends Response
{
    public function getRate(): ?string
    {
        $response = $this->getDecodedResponse();

        return $response['price'] ?? null;
    }

}
