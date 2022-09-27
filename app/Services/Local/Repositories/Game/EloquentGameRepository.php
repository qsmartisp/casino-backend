<?php

namespace App\Services\Local\Repositories\Game;

use App\Enums\Game\Status;
use App\Models\Game;
use App\Services\Local\Repositories\Contracts\GameRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EloquentGameRepository implements GameRepository
{
    public function all(array $columns = ['*'])
    {
        return Game::all();
    }

    public function findBySlug(string $slug)
    {
        return Game::query()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function filterGamesWithPagination(
        int $perPage,
        int $page,
        ?string $name,
        ?int $providerId,
        ?string $tag,
        ?array $providerIds,
    ): Paginator {
        return Game::query()
            ->with([
                'images' => (static fn(BelongsToMany $query) => $query->select(['files.id', 'files.path'])),
                'provider' => (static fn(BelongsTo $query) => $query->select(['id', 'slug', 'name'])),
            ])
            ->where('status', Status::Enabled->value)
            ->when($name, function (Builder $q, string $name) {
                return $q->whereRaw('LOWER(name) like ?', '%' . mb_strtolower($name) . '%');
            })
            ->when($providerId, function (Builder $q, int $providerId) {
                return $q->where('provider_id', $providerId);
            })
            ->when($tag, function (Builder $q, string $tag) {
                return $q->whereRelation('tags', 'name', $tag);
            })
            ->when($providerIds, function (Builder $q, array $providerIds) {
                return $q->whereIn('provider_id', $providerIds);
            })
            ->simplePaginate($perPage);
    }
}
