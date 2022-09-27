<?php

namespace App\Services\Local\Repositories;

use App\Models\Provider;
use App\Services\Local\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method Provider store(array $options)
 * @method Provider firstOrCreate(array $attributes = [], array $values = [])
 * @method null|Provider first(array $attributes = [])
 */
class ProviderRepository extends Repository
{
    public function find(int $id): Provider
    {
        return $this->query()->find($id);
    }

    public function findBySlug(string $slug): Provider
    {
        return $this->query()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getBySlug(string $slug): ?Provider
    {
        return $this->query()
            ->where('slug', $slug)
            ->first();
    }

    public function deleteByNames(array $names): int
    {
        return $this->query()
            ->whereIn('name', $names)
            ->delete();
    }

    public function query(): Provider|Builder
    {
        return Provider::query();
    }
}
