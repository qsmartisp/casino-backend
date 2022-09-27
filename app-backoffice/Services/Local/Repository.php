<?php

namespace AppBackoffice\Services\Local;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class Repository
{
    public function store(array $options): Model
    {
        return $this->query()->create($options);
    }

    public function deleteById(int $id): int
    {
        return $this->query()
            ->where('id', $id)
            ->delete();
    }

    public function deleteByIds(array $ids): int
    {
        return $this->query()
            ->whereIn('id', $ids)
            ->delete();
    }

    public function firstOrCreate(array $attributes = [], array $values = []): Model
    {
        return $this->query()->firstOrCreate($attributes, $values);
    }

    public function first(array $attributes = []): ?Model
    {
        return $this->query()->first($attributes);
    }

    public function update(Model $model, array $options): bool
    {
        return $model->update($options);
    }

    public function chunk(int $count, callable $callback): bool
    {
        return $this->query()->chunk($count, $callback);
    }

    public function all(array $columns = ['*']): Collection
    {
        /** @var Builder|Model $query */
        $query = $this->query();

        return $query->get($columns);
    }

    public function truncate(): void
    {
        $this->query()->truncate();
    }

    abstract public function query(): Builder|Model;
}
