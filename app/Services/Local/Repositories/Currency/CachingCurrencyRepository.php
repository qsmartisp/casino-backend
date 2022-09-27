<?php

namespace App\Services\Local\Repositories\Currency;

use App\Services\Local\Repositories\Contracts\CurrencyRepository;
use Illuminate\Cache\CacheManager;

class CachingCurrencyRepository implements CurrencyRepository
{
    public function __construct(
        protected CurrencyRepository $currencies,
        protected CacheManager $cache,
    ) {
    }

    public function all(array $columns = ['*'])
    {
        return $this->cache->remember(
            key: 'currencies.all',
            ttl: null,
            callback: fn() => $this->currencies->all($columns),
        );
    }

    public function allByCode(array $codes)
    {
        return $this->cache->remember(
            key: 'currencies.allByCode.' . implode("_", $codes),
            ttl: null,
            callback: fn() => $this->currencies->allByCode($codes),
        );
    }
}
