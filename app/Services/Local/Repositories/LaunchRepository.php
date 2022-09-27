<?php

namespace App\Services\Local\Repositories;

use App\Models\Launch;
use App\Services\Local\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method Launch store(array $options)
 */
class LaunchRepository extends Repository
{
    public function find(int $id): Launch
    {
        return $this->query()->find($id);
    }

    public function query(): Launch|Builder
    {
        return Launch::query();
    }
}
