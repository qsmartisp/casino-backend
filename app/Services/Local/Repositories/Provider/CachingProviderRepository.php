<?php

namespace App\Services\Local\Repositories\Provider;

use App\Services\Local\Repositories\Contracts\ProviderRepository;
use Illuminate\Cache\CacheManager;

class CachingProviderRepository implements ProviderRepository
{
    public function __construct(
        protected ProviderRepository $providers,
        protected CacheManager $cache,
    ) {
    }

    public function all(array $columns = ['*'], array $with = [])
    {
        return $this->cache->remember(
            key: 'providers.all',
            ttl: null,
            callback: fn() => $this->providers->all($columns, $with),
        );
    }
}
