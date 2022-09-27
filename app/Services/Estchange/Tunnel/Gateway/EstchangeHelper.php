<?php

namespace App\Services\Estchange\Tunnel\Gateway;

use App\Services\Estchange\Tunnel\Gateway\Enums\Coin;

class EstchangeHelper
{
    public static function generateClientId(
        int $id,
        string $coin,
        string $currency,
        string $delim = '_',
        string $prefix = 'CBC',
    ): string {
        return $prefix
            . $delim . $id
            . $delim . $coin
            . $delim . $currency;
    }

    public static function isCryptoCurrency(string $code): bool
    {
        return !is_null(Coin::tryFrom($code));
    }
}