<?php

namespace App\Services\Local\Repositories\Country;

use App\Services\Local\Repositories\Contracts\CountryRepository;
use Illuminate\Cache\CacheManager;

class CachingCountryRepository implements CountryRepository
{
    public function __construct(
        protected CountryRepository $countries,
        protected CacheManager $cache,
    ) {
    }

    public function all(array $columns = ['*'])
    {
        return $this->cache->remember(
            key: 'countries.all',
            ttl: null,
            callback: fn() => $this->countries->all($columns),
        );
    }
}
