<?php

namespace App\Services\Local\Repositories;

use App\Enums\Game\Status;
use App\Models\Aggregator;
use App\Models\Game;
use App\Services\Local\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @method Game store(array $options)
 */
class GameRepository extends Repository
{
    public function find(int $id): Game
    {
        return $this->query()->find($id);
    }

    public function findBySlug(string $slug): Game
    {
        return $this->query()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getByAggregator(Aggregator $aggregator): Collection
    {
        return $this->query()
            ->where('aggregator_id', $aggregator->id)
            ->get();
    }

    public function query(): Game|Builder
    {
        return Game::query();
    }

    public function filterGames(?string $name, ?int $providerId, ?string $tag, ?array $providerIds): Builder
    {
        return $this->query()
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
            });
    }
}
