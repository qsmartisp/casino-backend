<?php

namespace App\Services\Binance;

use App\Services\Binance\Responses\CurrencyRateResponse;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class BinanceService
{
    public function __construct(
        protected SenderInterface $sender,
    )
    {
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getCurrencyRate(
        string $symbol,
    ): CurrencyRateResponse
    {
        return new CurrencyRateResponse(
            $this->sender->send(
                apiUrl: "api/v3/ticker/price?symbol={$symbol}",
                method: SenderInterface::METHOD_GET,
            )
        );
    }


}
