<?php

namespace App\Services\Local\Repositories\Game;

use App\Services\Local\Repositories\Contracts\GameRepository;
use Illuminate\Cache\CacheManager;

class CachingGameRepository implements GameRepository
{
    public function __construct(
        protected GameRepository $games,
        protected CacheManager $cache,
    ) {
    }

    public function all(array $columns = ['*'])
    {
        return $this->cache->remember(
            key: 'games.all',
            ttl: null,
            callback: fn() => $this->games->all($columns),
        );
    }

    public function findBySlug(string $slug)
    {
        return $this->cache->remember(
            key: 'games.findBySlug.' . $slug,
            ttl: null,
            callback: fn() => $this->games->findBySlug($slug),
        );
    }

    public function filterGamesWithPagination(
        int $perPage,
        int $page,
        ?string $name,
        ?int $providerId,
        ?string $tag,
        ?array $providerIds,
    ) {
        return $this->cache->remember(
            key: "games.filtered"
            . ".perPage$perPage"
            . ".page$page"
            . ($name ? ".name$name" : '')
            . ($providerId ? ".providerId$providerId" : '')
            . ($tag ? ".tag$tag" : '')
            . ($providerIds ? '.' . implode('_', $providerIds) : ''),

            ttl: 3600, // todo
            callback: fn() => $this->games->filterGamesWithPagination(
                $perPage,
                $page,
                $name,
                $providerId,
                $tag,
                $providerIds,
            ),
        );
    }
}
