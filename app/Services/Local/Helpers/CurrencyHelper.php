<?php

namespace App\Services\Local\Helpers;

use App\Enums\Currency\CryptoCurrency;
use App\Models\Currency;

class CurrencyHelper
{
    public static function isCryptoCurrency(Currency $currency): bool
    {
        return !is_null(CryptoCurrency::tryFrom($currency->name));
    }
}
