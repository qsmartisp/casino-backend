<?php

namespace App\Services;

use App\Models\Currency;
use Bavix\Wallet\Internal\Service\MathServiceInterface;
use Bavix\Wallet\Services\ExchangeServiceInterface;

class WalletExchangeService implements ExchangeServiceInterface
{
    private MathServiceInterface $mathService;

    public function __construct(MathServiceInterface $mathService)
    {
        $this->mathService = $mathService;
    }

    /** @param float|int|string $amount */
    public function convertTo(string $fromCurrency, string $toCurrency, $amount): string
    {
        $currency = Currency::query()->byCode($fromCurrency)->with('binanceRate')->first();

        return $this->mathService->div($amount, $currency->binanceRate->rate ?? 1);
    }

    /**
     * @param string $currency
     * @param float $amount
     * @return float
     */
    public function getCpAmount(string $currency, float $amount): float
    {
        $coefficient = 0.05; // if 20EUR = 1CP then 1CP = 0.05EUR
        $amount = $this->convertTo($currency, 'EUR', $amount);

        return $amount * $coefficient;
    }


}
