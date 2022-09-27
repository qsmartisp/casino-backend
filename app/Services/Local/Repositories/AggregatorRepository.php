<?php

namespace App\Services\Local\Repositories;

use App\Models\Aggregator;
use App\Services\Local\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method Aggregator store(array $options)
 * @method Aggregator firstOrCreate(array $attributes = [], array $values = [])
 */
class AggregatorRepository extends Repository
{
    public function find(int $id): Aggregator
    {
        return $this->query()->find($id);
    }

    public function findBySlug(string $slug): Aggregator
    {
        return $this->query()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function deleteByNames(array $names): int
    {
        return $this->query()
            ->whereIn('name', $names)
            ->delete();
    }

    public function query(): Aggregator|Builder
    {
        return Aggregator::query();
    }
}
