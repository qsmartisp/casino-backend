<?php

namespace App\Services\Estchange\Tunnel\Gateway;

use App\Services\Estchange\Tunnel\Gateway\Responses\CurrencyRateResponse;
use App\Services\Estchange\Tunnel\Gateway\Responses\WalletAddressResponse;
use App\Services\Estchange\Tunnel\Gateway\Responses\WithdrawalResponse;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class EstchangeService
{
    public function __construct(
        protected SenderInterface $sender,
    ) {
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function createWallet(
        string $clientId,
        string $cryptoCurrency,
        ?string $currency = null,
    ): WalletAddressResponse {
        return match ($currency === null) {
            true => $this->createWalletOnlyCrypto($clientId, $cryptoCurrency),
            false => $this->createWalletWithFiat($clientId, $cryptoCurrency, $currency),
        };
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    protected function createWalletOnlyCrypto(
        string $clientId,
        string $cryptoCurrency,
    ): WalletAddressResponse {
        return new WalletAddressResponse(
            $this->sender->send(
                apiUrl: "/{$cryptoCurrency}/address/{$clientId}",
                method: SenderInterface::METHOD_POST,
            )
        );
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    protected function createWalletWithFiat(
        string $clientId,
        string $cryptoCurrency,
        ?string $currency = null,
    ): WalletAddressResponse {
        return new WalletAddressResponse(
            $this->sender->send(
                apiUrl: "/{$cryptoCurrency}/{$currency}/address/{$clientId}",
                method: SenderInterface::METHOD_GET,
            )
        );
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getCurrencyRate(
        string $cryptoCurrency,
        string $currency,
    ): CurrencyRateResponse {
        return new CurrencyRateResponse(
            $this->sender->send(
                apiUrl: "/rate/{$cryptoCurrency}:{$currency}",
                method: SenderInterface::METHOD_GET,
            )
        );
    }

    public function withdrawal(
        float $amount,
        string $address,
        string $coin,
        ?string $currency = null,
    ): WithdrawalResponse {
        return match ($currency === null) {
            true => $this->withdrawalCrypto($amount, $address, $coin),
            false => $this->withdrawalFiat($amount, $address, $coin, $currency),
        };
    }

    protected function withdrawalCrypto(
        float $amount,
        string $address,
        string $coin,
    ): WithdrawalResponse {
        return new WithdrawalResponse(
            $this->sender->send(
                apiUrl: "/withdrawal/coin/{$coin}",
                method: SenderInterface::METHOD_POST,
                params: [
                    'address' => $address,
                    'amount' => $amount,
                    'amountType' => "spend", // or receive
                    'reference' => "USD", // todo
                ],
            )
        );
    }

    protected function withdrawalFiat(
        float $amount,
        string $address,
        string $coin,
        string $currency,
    ): WithdrawalResponse {
        return new WithdrawalResponse(
            $this->sender->send(
                apiUrl: "/outgoingrequest/outputcurrency/{$coin}/debitingcurrency/{$currency}/calculationCurrency/{$currency}",
                method: SenderInterface::METHOD_POST,
                params: [
                    'address' => $address,
                    'amount' => $amount,
                    'paidBy' => "counterparty", // or myself
                    'reference' => $currency,
                ],
            )
        );
    }
}
