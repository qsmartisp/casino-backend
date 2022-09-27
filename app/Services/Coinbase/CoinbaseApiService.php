<?php

namespace App\Services\Coinbase;

use App\Services\Coinbase\Responses\ChargeResponse;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class CoinbaseApiService
{
    public function __construct(
        protected SenderInterface $sender,
    ) {
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function createCharge(
        string $amount,
        string $currency,
        string $name,
        string $description,
        string $pricingType = 'fixed_price', // or 'no_price'
        array $metadata = [],
        ?string $redirectUrl = null,
        ?string $cancelUrl = null,
    ): ChargeResponse {
        return new ChargeResponse(
            $this->sender->send(
                apiUrl: 'charges',
                method: SenderInterface::METHOD_POST,
                params: [
                    'name' => $name,
                    'description' => $description,
                    'pricingType' => $pricingType,
                    'amount' => $amount,
                    'currency' => $currency,
                    'metadata' => $metadata,
                    'redirectUrl' => $redirectUrl,
                    'cancelUrl' => $cancelUrl,
                ],
            )
        );
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function listCharges(): ChargeResponse
    {
        return new ChargeResponse(
            $this->sender->send('charges', SenderInterface::METHOD_GET)
        );
    }
}
