<?php

namespace App\Services\Local\Repositories\Contracts;

interface GameRepository
{
    public function all(array $columns = ['*']);

    public function findBySlug(string $slug);

    public function filterGamesWithPagination(
        int $perPage,
        int $page,
        ?string $name,
        ?int $providerId,
        ?string $tag,
        ?array $providerIds,
    );
}
