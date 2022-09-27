<?php

namespace App\Services\Local\Repositories\Provider;

use App\Models\Provider;
use App\Services\Local\Repositories\Contracts\ProviderRepository;

class EloquentProviderRepository implements ProviderRepository
{
    public function all(array $columns = ['*'], array $with = [])
    {
        return Provider::query()
            ->with($with)
            ->get($columns);
    }
}
