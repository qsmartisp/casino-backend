<?php

namespace App\Services\Local\Repositories\Currency;

use App\Models\Currency;
use App\Services\Local\Repositories\Contracts\CurrencyRepository;

class EloquentCurrencyRepository implements CurrencyRepository
{
    public function all(array $columns = ['*'])
    {
        return Currency::all();
    }

    public function allByCode(array $codes)
    {
        return Currency::query()->whereIn('code', $codes)->get();
    }
}
